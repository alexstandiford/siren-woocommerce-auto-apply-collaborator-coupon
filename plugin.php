<?php
/*
 * Plugin Name: Siren Affiliates: Auto Apply Collaborator Coupons
 * Description: Automatically applies a collaborator's coupon code when their referral link triggers an engagement.
 * Author: Novatorius, LLC
 * Author URI: https://sirenaffiliates.com
 * Version: 1.0.2
 */

use PHPNomad\Core\Facades\Event;
use PHPNomad\Core\Facades\InstanceProvider;
use PHPNomad\Core\Facades\Logger;
use PHPNomad\Datastore\Exceptions\RecordNotFoundException;
use PHPNomad\Utils\Helpers\Arr;
use Siren\Collaborators\Core\Facades\CollaboratorAliases;
use Siren\Core\Interfaces\VersionProvider;
use Siren\Engagements\Core\Events\EngagementsTriggered;
use Siren\Engagements\Core\Models\Engagement;

add_action('siren_ready', function () {
    $version = InstanceProvider::get(VersionProvider::class);

    if (version_compare($version->getVersion(), '3.0.0', '<')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' .
                esc_html__("Siren's Auto Apply Collaborator Coupons plugin requires Siren 3.0.0 or greater. Coupons will not apply until you update Siren.", 'siren-auto-apply-coupon') .
                '</p></div>';
        });
    }

    Event::attach(EngagementsTriggered::class, function (EngagementsTriggered $event) {
        if ($event->getTriggerStrategyId() === 'referredSiteVisit') {
            /** @var Engagement $engagement */
            $engagement = Arr::first($event->getEngagements());

            if (!$engagement) {
                Logger::info('Coupon was not applied because no engagements were triggered.');
                return;
            }

            try {
                if (isset(WC()->session) && ! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }

                $alias = CollaboratorAliases::getAliasForCollaborator($engagement->getCollaboratorId(), 'coupon');
                WC()->cart->apply_coupon($alias->getCode());
                WC()->cart->calculate_totals();
                WC()->session->set('sirenCollaboratorCouponCode', $alias->getCode());
            } catch (RecordNotFoundException $e) {
                // Collaborator does not have a coupon code.
                Logger::info('Coupon was not applied because no coupon code was found for this collaborator.');
            }
        }
    });

    // Add the coupon code when an item is added to the cart.
    add_action('woocommerce_add_to_cart', function () {
        $couponCode = WC()->session->get('sirenCollaboratorCouponCode');
        if (empty($couponCode)) {
            return;
        }

        $appliedCoupons = Arr::map(WC()->session->get('applied_coupons'), fn($code) => strtolower($code));
        $couponCode = strtolower($couponCode);

        if(in_array($couponCode, $appliedCoupons)){
          return;
        }

        WC()->cart->apply_coupon($couponCode);
    });
});
