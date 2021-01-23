<?php
/* Get the core config */
if (!file_exists(dirname(dirname(__FILE__)).'/config.core.php')) {
    die('ERROR: missing '.dirname(dirname(__FILE__)).'/config.core.php file defining the MODX core path.');
}

/* Boot up MODX */
echo "Loading modX...\n";
require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
echo "Initializing manager...\n";
$modx->initialize('mgr');
$modx->getService('error','error.modError', '', '');
$modx->setLogTarget('HTML');

$componentPath = dirname(dirname(__FILE__));

/** @var Commerce $commerce */
//$modx->setOption('commerce.core_path', $componentPath.'/core/components/commerce/');
//$commerce = $modx->getService('commerce','Commerce', $componentPath.'/core/components/commerce/model/commerce/');


$elements = [
    'modTemplate' => [
        'Blue - Home' => $componentPath . '/core/components/commercetheme_blue/elements/templates/home.tpl',
        'Blue - Cart' => $componentPath . '/core/components/commercetheme_blue/elements/templates/cart.tpl',
        'Blue - Category' => $componentPath . '/core/components/commercetheme_blue/elements/templates/category.tpl',
        'Blue - Checkout' => $componentPath . '/core/components/commercetheme_blue/elements/templates/checkout.tpl',
        'Blue - Product' => $componentPath . '/core/components/commercetheme_blue/elements/templates/product.tpl',
        'Blue - Account' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account.tpl',
        'Blue - Account login' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_login.tpl',
        'Blue - Account register' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_register.tpl',
        'Blue - Account activate registration' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_activate_registration.tpl',
        'Blue - Account thank you registration' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_thank_you_registration.tpl',
        'Blue - Account forgot password' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_password.tpl',
        'Blue - Account Order' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_order.tpl',
        'Blue - Account Orders' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_orders.tpl',
        'Blue - Account Edit profile' => $componentPath . '/core/components/commercetheme_blue/elements/templates/account_edit_pofile.tpl',
    ],

    'modChunk' => [
        'ctblue.category_list' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/category_list.tpl',
        'ctblue.item_list' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/item_list.tpl',
        'ctblue.category_list_home_outer_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/category_list_home_outer_chunk.tpl',
        'ctblue.category_list_home_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/category_list_home_chunk.tpl',
        'ctblue.category_list_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/category_list_chunk.tpl',
        'ctblue.category_list_outer_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/category_list_outer_chunk.tpl',
        'ctblue.related_list' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/related_list.tpl',
        'ctblue.related_outer' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/related_outer.tpl',
        'ctblue.related_outer_list' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/related_outer_list.tpl',
        'ctblue.login_form' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/login_form.tpl',
        'ctblue.logout_form' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/logout_form.tpl',
        'ctblue.forgot_pass' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/forgot_pass.tpl',
        'ctblue.account_form' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/account_form.tpl',
        'ctblue.register_form' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/register_form.tpl',
        'ctblue.register_email' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/register_email.tpl',
        'ctblue.update_profile_form' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/update_profile_form.tpl',
        'ctblue.profile_details' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/profile_details.tpl',
        'ctblue.login_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/login_chunk.tpl',
        'ctblue.tag_list_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/tag_list_chunk.tpl',
        'ctblue.tag_outer_chunk' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/tag_outer_chunk.tpl',
        'ctblue.profile_menu' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/profile_menu.tpl',
        'ctblue.footer' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/footer.tpl',
        'ctblue.header' => $componentPath . '/core/components/commercetheme_blue/elements/chunks/header.tpl',
    ],
];

if (!createObject('modCategory', [
    'category' => 'Blue'
], 'category', true)) {
    echo "Error creating category; halting.\n";
    exit(1);
}

$category = $modx->getObject('modCategory', ['category' => 'Blue']);
$categoryId = $category ? $category->get('id') : 0;

foreach ($elements as $type => $records) {
    $nameFld = $type === 'modTemplate' ? 'templatename' : 'name';
    foreach ($records as $name => $file) {
        if (!createObject($type, [
            $nameFld => $name,
            'static' => true,
            'static_file' => $file,
            'category' => $categoryId,
        ],$nameFld, true)) {
            echo "Error creating {$type} {$name}.\n";
        }
    }
}
//ctblue.assets_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.assets_url',
    'value' => '/assets/components/commercetheme_blue/'
], 'key', false)) {
    echo "Error creating ctblue.assets_url system setting.\n";
}

//ctblue.account_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.account_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.account_page_id system setting.\n";
}

//ctblue.edit_profile_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.edit_profile_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.edit_profile_page_id system setting.\n";
}

//ctblue.password_reset_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.password_reset_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.password_reset_page_id system setting.\n";
}

//ctblue.registration_please_activate_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.registration_please_activate_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.registration_please_activate_page_id system setting.\n";
}

//ctblue.registration_activation_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.registration_activation_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.registration_activation_page_id system setting.\n";
}

//ctblue.registration_thank_you_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.registration_thank_you_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.registration_thank_you_page_id system setting.\n";
}

//ctblue.you_are_logout_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.you_are_logout_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.you_are_logout_page_id system setting.\n";
}

//ctblue.edit_profile_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.edit_profile_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.edit_profile_page_id system setting.\n";
}

//ctblue.account_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.account_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.account_page_id system setting.\n";
}
//ctblue.forgot_password_page_id
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.forgot_password_page_id',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.forgot_password_page_id system setting.\n";
}

//ctblue.footer_header_one
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.footer_header_one',
    'value' => 'Pages'
], 'key', false)) {
    echo "Error creating ctblue.footer_header_one system setting.\n";
}

//ctblue.footer_header_two
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.footer_header_two',
    'value' => 'Quick links'
], 'key', false)) {
    echo "Error creating ctblue.footer_header_two system setting.\n";
}

//ctblue.footer_content
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.footer_content',
    'value' => '<p>Build a custom webshop quickly, with starter pack <em>Blue</em>.</p>'
], 'key', false)) {
    echo "Error creating ctblue.footer_content system setting.\n";
}

//ctblue.footer_bottom_row_content
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.footer_bottom_row_content',
    'value' => '<p>Powered by <a href="https://modmore.com/commerce/" target="_blank" rel="noopener">Commerce</a>.</p><p>&copy; All rights reserved.</p>'
], 'key', false)) {
    echo "Error creating ctblue.footer_bottom_row_content system setting.\n";
}

//ctblue.quick_link_01_text
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_01_text',
    'value' => 'Account'
], 'key', false)) {
    echo "Error creating ctblue.quick_link_01_text system setting.\n";
}

//ctblue.quick_link_01_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_01_url',
    'value' => '[[~[[++ctblue.account_page_id]]]]'
], 'key', false)) {
    echo "Error creating ctblue.quick_link_01_url system setting.\n";
}

//ctblue.quick_link_02_text
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_02_text',
    'value' => 'Profile'
], 'key', false)) {
    echo "Error creating ctblue.quick_link_02_text system setting.\n";
}

//ctblue.quick_link_02_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_02_url',
    'value' => '[[~[[++ctblue.edit_profile_page_id]]]]'
], 'key', false)) {
    echo "Error creating ctblue.quick_link_02_url system setting.\n";
}

//ctblue.quick_link_03_text
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_03_text',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_03_text system setting.\n";
}

//ctblue.quick_link_03_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_03_url',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_03_url system setting.\n";
}

//ctblue.quick_link_04_text
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_04_text',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_04_text system setting.\n";
}

//ctblue.quick_link_04_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_04_url',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_04_url system setting.\n";
}

//ctblue.quick_link_05_text
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_05_text',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_05_text system setting.\n";
}

//ctblue.quick_link_05_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_05_url',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_05_url system setting.\n";
}

//ctblue.quick_link_06_text
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_06_text',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_06_text system setting.\n";
}

//ctblue.quick_link_06_url
if (!createObject('modSystemSetting', [
    'key' => 'ctblue.quick_link_06_url',
    'value' => ''
], 'key', false)) {
    echo "Error creating ctblue.quick_link_06_url system setting.\n";
}
if (!createObject('modSystemSetting', [
    'key' => 'footer.facebook_url',
    'value' => 'https://facebook.com/moreformodx'
], 'key', false)) {
    echo "Error creating footer.facebook_url system setting.\n";
}
if (!createObject('modSystemSetting', [
    'key' => 'footer.twitter_url',
    'value' => 'https://twitter.com/modmore'
], 'key', false)) {
    echo "Error creating footer.twitter_url system setting.\n";
}
if (!createObject('modSystemSetting', [
    'key' => 'footer.instagram_url',
    'value' => ''
], 'key', false)) {
    echo "Error creating footer.instagram_url system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'commerce_matrix',
    'name' => 'product_matrix',
    'caption' => 'Products',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'richtext',
    'name' => 'hero_content',
    'caption' => 'Hero content',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'textfield',
    'name' => 'ctblue_featured_product',
    'caption' => 'Featublue product',
    'description' => 'Make this product a featured product.',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'image',
    'name' => 'hero_image',
    'caption' => 'Hero background image',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'image',
    'name' => 'ctblue_category_image',
    'caption' => 'Category image',
    'description' => 'The image to display for this category.',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'textfield',
    'name' => 'product_tab_show',
    'caption' => 'Show tab section',
    'description' => 'Enter true to show the tabs.',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'richtext',
    'name' => 'product_tab_1_content',
    'caption' => 'Tab 1 content',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'textfield',
    'name' => 'product_tab_1_title',
    'caption' => 'Tab 1 title',
    'description' => 'Leave blank to not show the tab.',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'richtext',
    'name' => 'product_tab_2_content',
    'caption' => 'Tab 2 content',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'textfield',
    'name' => 'product_tab_2_title',
    'caption' => 'Tab 2 title',
    'description' => 'Leave blank to not show the tab.',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'richtext',
    'name' => 'product_tab_3_content',
    'caption' => 'Tab 3 content',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

if (!createObject('modTemplateVar', [
    'type' => 'textfield',
    'name' => 'product_tab_3_title',
    'caption' => 'Tab 3 title',
    'description' => 'Leave blank to not show the tab.',
], 'name', false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_matrix']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'featured_product']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'hero_content']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Home']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'hero_image']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Home']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_show']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_1_title']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_1_content']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_2_title']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_2_content']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_3_title']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'product_tab_3_content']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Product']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

$tv = $modx->getObject('modTemplateVar', ['name' => 'ctblue_category_image']);
$tvId = $tv ? $tv->get('id') : 0;
$tmpl = $modx->getObject('modTemplate', ['templatename' => 'Blue - Category']);
$tmplId = $tmpl ? $tmpl->get('id') : 0;
if (!createObject('modTemplateVarTemplate', [
    'tmplvarid' => $tvId,
    'templateid' => $tmplId,
], ['tmplvarid', 'templateid'], false)) {
    echo "Error creating modTemplateVar system setting.\n";
}

// Clear the cache
$modx->cacheManager->refresh();

echo "Done.\n";


/**
 * Creates an object.
 *
 * @param string $className
 * @param array $data
 * @param string $primaryField
 * @param bool $update
 * @return bool
 */
function createObject ($className = '', array $data = array(), $primaryField = '', $update = true) {
    global $modx;
    /* @var xPDOObject $object */
    $object = null;

    /* Attempt to get the existing object */
    if (!empty($primaryField)) {
        if (is_array($primaryField)) {
            $condition = array();
            foreach ($primaryField as $key) {
                $condition[$key] = $data[$key];
            }
        }
        else {
            $condition = array($primaryField => $data[$primaryField]);
        }
        $object = $modx->getObject($className, $condition);
        if ($object instanceof $className) {
            if ($update) {
                $object->fromArray($data);
                return $object->save();
            } else {
                $condition = $modx->toJSON($condition);
                echo "Skipping {$className} {$condition}: already exists.\n";
                return true;
            }
        }
    }

    /* Create new object if it doesn't exist */
    if (!$object) {
        $object = $modx->newObject($className);
        $object->fromArray($data, '', true);
        return $object->save();
    }

    return false;
}
