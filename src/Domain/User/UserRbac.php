<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\User;

final class UserRbac
{
    public function getRoles(string $user)
    {
        $roles = [];

        switch($user) {
            case 'system@system':
            case 'admin@admin':
            case 'demo@demo':
            case 'sand@box':
            case 'admin@anotar':
            case 'admin@aceitar':
            case 'admin@contratar':
                $roles = [
                    'ROLE_ADMIN',
                ];

                break;
            default:
                $roles = [
                    'ROLE_USER_ORDER_*',
                    'ROLE_USER_ORDER_INDEX',
                    'ROLE_USER_ORDER_CREATE',
                    'ROLE_USER_ORDER_READ',
                    'ROLE_USER_ORDER_UPDATE',

                    'ROLE_USER_CUSTOMER_INDEX',
                    'ROLE_USER_CUSTOMER_READ',
                    'ROLE_USER_CITY_INDEX',

                    'ROLE_USER_WORKER_INDEX',
                    'ROLE_USER_WORKER_READ',

                    'ROLE_USER_VEHICLE_INDEX',
                    'ROLE_USER_VEHICLE_READ',
                    'ROLE_USER_VEHICLETYPE_INDEX',
                    'ROLE_USER_VEHICLEBRAND_INDEX',
                    'ROLE_USER_VEHICLESERIES_INDEX',

                    'ROLE_USER_PRODUCT_INDEX',
                    'ROLE_USER_ORDER_DETAIL_READ',
                    'ROLE_USER_PAYMENTMETHOD_INDEX',
                    'ROLE_USER_PAYMENT_READ',
                    'ROLE_USER_BILL_READ',
                ];

                break;
        }

        return $roles;
    }
}
