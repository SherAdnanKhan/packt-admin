<?php

namespace App\Http\Composers;

class SideNavComposer
{
    public function compose($view)
    {
        // 'side_nav_items' cannot be a 'const' because the 'asset()' function would not work otherwise
        $side_nav_items = [
            [
                'link' => 'old/auth',
                'imageURL' => asset('images/sidenav/auth.svg'),
                'text' => 'Auth',
            ],
            [
                'link' => 'old/credits',
                'imageURL' => asset('images/sidenav/credits.svg'),
                'text' => 'Credits',
            ],
            [
                'link' => 'old/gifts',
                'imageURL' => asset('images/sidenav/gifts.svg'),
                'text' => 'Gifts',
            ],
            [
                'link' => 'old/orders',
                'imageURL' => asset('images/sidenav/orders.svg'),
                'text' => 'Orders',
            ],
            [
                'link' => 'manual-order',
                'imageURL' => asset('images/sidenav/manual_order.svg'),
                'text' => 'Manual Order',
            ],
            [
                'link' => 'old/free-learning',
                'imageURL' => asset('images/sidenav/free_learning.svg'),
                'text' => 'Free Learning',
            ],
            [
                'link' => 'old/users',
                'imageURL' => asset('images/sidenav/users.svg'),
                'text' => 'Users',
            ],
            [
                'link' => 'new-account',
                'imageURL' => asset('images/sidenav/new_account.svg'),
                'text' => 'Create New Account',
            ],
            [
                'link' => 'old/products',
                'imageURL' => asset('images/sidenav/products.svg'),
                'text' => 'Products',
            ],
            [
                'link' => 'old/authors',
                'imageURL' => asset('images/sidenav/authors.svg'),
                'text' => 'Authors',
            ],
            [
                'link' => 'old/publish-product',
                'imageURL' => asset('images/sidenav/publish_product.svg'),
                'text' => 'Publish Product',
            ],
            [
                'link' => 'old/lms',
                'imageURL' => asset('images/sidenav/lms.svg'),
                'text' => 'LMS',
            ],
            [
                'link' => 'old/timed-unlock',
                'imageURL' => asset('images/sidenav/timed_unlock.svg'),
                'text' => 'Timed Unlock',
            ],
            [
                'link' => 'old/system-status',
                'imageURL' => asset('images/sidenav/system_status.svg'),
                'text' => 'System Status',
            ],
        ];
        $view->with('side_nav_items', $side_nav_items);
    }
}
