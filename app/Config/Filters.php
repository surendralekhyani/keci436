<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
		'auth' => \App\Filters\Auth::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
            'csrf' =>['except' => ['Organisation_Controller.*', 
									'Customers_Controller.*',
									'ItemGroups_Controller.*',
									'Items_Controller.*',
									'EditItems_Controller.*',
									'EditItemsGroup_Controller.*',
									'Quotation_Controller.*',
									'Purchase_Controller.*',
									'Sale_Controller.*',
									'PaymentReceipt_Controller.*',
									'Reminders_Controller.*',
									'Requirement_Controller.*',
									'Replacement_Controller.*',
									'RptLedger_Controller.*',
									'RptDayBook_Controller.*',
									'RptLedgerItem_Controller.*',
									'RptItemsPurchaseAndSold_Controller.*',
									'RptItemsPurchaseAndSoldPaging_Controller.*',
									'rptitemspurchaseandsoldpaging.*',
									'RptDues_Controller.*',
									'RptSearch_Controller.*',
									'User_Controller.*',
									'Right_Controller.*',
									'Changepwd_Controller.*',
									'Changepwdadmin_Controller.*',
									'Backupdata_Controller.*',
									'AdminRights_Controller.*',
									'Duplicates_Controller.*',
									'DuplicateCustomers_Controller.*',
									'AddressBook_Controller.*',
									'Conclusions_Controller.*',
									'ToDo_Controller.*',
									'Family_Controller.*',
									'DailyCash_Controller.*']],
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [];
}
