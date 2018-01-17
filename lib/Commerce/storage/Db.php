<?php
namespace Fuse\Commerce;

class Db {
    private $charset_collate;
    private $wpdb;

    function __construct() {
        global $wpdb;
        $this->wpdb             = $wpdb;
        $this->charset_collate  = $wpdb->get_charset_collate();

        $this->coupons_table();
        $this->plans_table();
        $this->countries_table();

    }

    private function coupons_table() {
        global $wpdb;

        $table_name = $this->wpdb->prefix . 'commerce_coupon';

        // Parent table
        $sql = "CREATE TABLE {$table_name}s (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          coupon_id tinytext NOT NULL,
          vendor tinytext NOT NULL,
          status tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        // Meta table
        $sql = "CREATE TABLE {$table_name}meta (
          meta_id bigint(20) NOT NULL AUTO_INCREMENT,
          coupon_id tinytext NOT NULL,
          meta_key varchar(255) DEFAULT NULL,
          meta_value longtext,
          PRIMARY KEY  (meta_id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        $wpdb->couponmeta = $wpdb->prefix . 'commerce_couponmeta';

    }

    private function plans_table() {
        global $wpdb;

        $table_name = $this->wpdb->prefix . 'commerce_plan';

        // Parent table
        $sql = "CREATE TABLE {$table_name}s (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          plan_id tinytext NOT NULL,
          vendor tinytext NOT NULL,
          status tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        // Meta table
        $sql = "CREATE TABLE {$table_name}meta (
          meta_id bigint(20) NOT NULL AUTO_INCREMENT,
          plan_id tinytext NOT NULL,
          meta_key varchar(255) DEFAULT NULL,
          meta_value longtext,
          PRIMARY KEY  (meta_id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        $wpdb->planmeta = $wpdb->prefix . 'commerce_planmeta';
    }

    private function countries_table() {
        global $wpdb;

        $table_name = $this->wpdb->prefix . 'countries';

        $sql = "CREATE TABLE {$table_name} (
        id int(11) NOT NULL auto_increment,
        country_code varchar(2) NOT NULL default '',
        country_name varchar(100) NOT NULL default '',
        PRIMARY KEY (id)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);

            $countries_sql = "INSERT INTO {$table_name} VALUES (null, 'AF', 'Afghanistan');
INSERT INTO {$table_name} VALUES (null, 'AL', 'Albania');
INSERT INTO {$table_name} VALUES (null, 'DZ', 'Algeria');
INSERT INTO {$table_name} VALUES (null, 'DS', 'American Samoa');
INSERT INTO {$table_name} VALUES (null, 'AD', 'Andorra');
INSERT INTO {$table_name} VALUES (null, 'AO', 'Angola');
INSERT INTO {$table_name} VALUES (null, 'AI', 'Anguilla');
INSERT INTO {$table_name} VALUES (null, 'AQ', 'Antarctica');
INSERT INTO {$table_name} VALUES (null, 'AG', 'Antigua and Barbuda');
INSERT INTO {$table_name} VALUES (null, 'AR', 'Argentina');
INSERT INTO {$table_name} VALUES (null, 'AM', 'Armenia');
INSERT INTO {$table_name} VALUES (null, 'AW', 'Aruba');
INSERT INTO {$table_name} VALUES (null, 'AU', 'Australia');
INSERT INTO {$table_name} VALUES (null, 'AT', 'Austria');
INSERT INTO {$table_name} VALUES (null, 'AZ', 'Azerbaijan');
INSERT INTO {$table_name} VALUES (null, 'BS', 'Bahamas');
INSERT INTO {$table_name} VALUES (null, 'BH', 'Bahrain');
INSERT INTO {$table_name} VALUES (null, 'BD', 'Bangladesh');
INSERT INTO {$table_name} VALUES (null, 'BB', 'Barbados');
INSERT INTO {$table_name} VALUES (null, 'BY', 'Belarus');
INSERT INTO {$table_name} VALUES (null, 'BE', 'Belgium');
INSERT INTO {$table_name} VALUES (null, 'BZ', 'Belize');
INSERT INTO {$table_name} VALUES (null, 'BJ', 'Benin');
INSERT INTO {$table_name} VALUES (null, 'BM', 'Bermuda');
INSERT INTO {$table_name} VALUES (null, 'BT', 'Bhutan');
INSERT INTO {$table_name} VALUES (null, 'BO', 'Bolivia');
INSERT INTO {$table_name} VALUES (null, 'BA', 'Bosnia and Herzegovina');
INSERT INTO {$table_name} VALUES (null, 'BW', 'Botswana');
INSERT INTO {$table_name} VALUES (null, 'BV', 'Bouvet Island');
INSERT INTO {$table_name} VALUES (null, 'BR', 'Brazil');
INSERT INTO {$table_name} VALUES (null, 'IO', 'British Indian Ocean Territory');
INSERT INTO {$table_name} VALUES (null, 'BN', 'Brunei Darussalam');
INSERT INTO {$table_name} VALUES (null, 'BG', 'Bulgaria');
INSERT INTO {$table_name} VALUES (null, 'BF', 'Burkina Faso');
INSERT INTO {$table_name} VALUES (null, 'BI', 'Burundi');
INSERT INTO {$table_name} VALUES (null, 'KH', 'Cambodia');
INSERT INTO {$table_name} VALUES (null, 'CM', 'Cameroon');
INSERT INTO {$table_name} VALUES (null, 'CA', 'Canada');
INSERT INTO {$table_name} VALUES (null, 'CV', 'Cape Verde');
INSERT INTO {$table_name} VALUES (null, 'KY', 'Cayman Islands');
INSERT INTO {$table_name} VALUES (null, 'CF', 'Central African Republic');
INSERT INTO {$table_name} VALUES (null, 'TD', 'Chad');
INSERT INTO {$table_name} VALUES (null, 'CL', 'Chile');
INSERT INTO {$table_name} VALUES (null, 'CN', 'China');
INSERT INTO {$table_name} VALUES (null, 'CX', 'Christmas Island');
INSERT INTO {$table_name} VALUES (null, 'CC', 'Cocos (Keeling) Islands');
INSERT INTO {$table_name} VALUES (null, 'CO', 'Colombia');
INSERT INTO {$table_name} VALUES (null, 'KM', 'Comoros');
INSERT INTO {$table_name} VALUES (null, 'CG', 'Congo');
INSERT INTO {$table_name} VALUES (null, 'CK', 'Cook Islands');
INSERT INTO {$table_name} VALUES (null, 'CR', 'Costa Rica');
INSERT INTO {$table_name} VALUES (null, 'HR', 'Croatia (Hrvatska)');
INSERT INTO {$table_name} VALUES (null, 'CU', 'Cuba');
INSERT INTO {$table_name} VALUES (null, 'CY', 'Cyprus');
INSERT INTO {$table_name} VALUES (null, 'CZ', 'Czech Republic');
INSERT INTO {$table_name} VALUES (null, 'DK', 'Denmark');
INSERT INTO {$table_name} VALUES (null, 'DJ', 'Djibouti');
INSERT INTO {$table_name} VALUES (null, 'DM', 'Dominica');
INSERT INTO {$table_name} VALUES (null, 'DO', 'Dominican Republic');
INSERT INTO {$table_name} VALUES (null, 'TP', 'East Timor');
INSERT INTO {$table_name} VALUES (null, 'EC', 'Ecuador');
INSERT INTO {$table_name} VALUES (null, 'EG', 'Egypt');
INSERT INTO {$table_name} VALUES (null, 'SV', 'El Salvador');
INSERT INTO {$table_name} VALUES (null, 'GQ', 'Equatorial Guinea');
INSERT INTO {$table_name} VALUES (null, 'ER', 'Eritrea');
INSERT INTO {$table_name} VALUES (null, 'EE', 'Estonia');
INSERT INTO {$table_name} VALUES (null, 'ET', 'Ethiopia');
INSERT INTO {$table_name} VALUES (null, 'FK', 'Falkland Islands (Malvinas)');
INSERT INTO {$table_name} VALUES (null, 'FO', 'Faroe Islands');
INSERT INTO {$table_name} VALUES (null, 'FJ', 'Fiji');
INSERT INTO {$table_name} VALUES (null, 'FI', 'Finland');
INSERT INTO {$table_name} VALUES (null, 'FR', 'France');
INSERT INTO {$table_name} VALUES (null, 'FX', 'France, Metropolitan');
INSERT INTO {$table_name} VALUES (null, 'GF', 'French Guiana');
INSERT INTO {$table_name} VALUES (null, 'PF', 'French Polynesia');
INSERT INTO {$table_name} VALUES (null, 'TF', 'French Southern Territories');
INSERT INTO {$table_name} VALUES (null, 'GA', 'Gabon');
INSERT INTO {$table_name} VALUES (null, 'GM', 'Gambia');
INSERT INTO {$table_name} VALUES (null, 'GE', 'Georgia');
INSERT INTO {$table_name} VALUES (null, 'DE', 'Germany');
INSERT INTO {$table_name} VALUES (null, 'GH', 'Ghana');
INSERT INTO {$table_name} VALUES (null, 'GI', 'Gibraltar');
INSERT INTO {$table_name} VALUES (null, 'GK', 'Guernsey');
INSERT INTO {$table_name} VALUES (null, 'GR', 'Greece');
INSERT INTO {$table_name} VALUES (null, 'GL', 'Greenland');
INSERT INTO {$table_name} VALUES (null, 'GD', 'Grenada');
INSERT INTO {$table_name} VALUES (null, 'GP', 'Guadeloupe');
INSERT INTO {$table_name} VALUES (null, 'GU', 'Guam');
INSERT INTO {$table_name} VALUES (null, 'GT', 'Guatemala');
INSERT INTO {$table_name} VALUES (null, 'GN', 'Guinea');
INSERT INTO {$table_name} VALUES (null, 'GW', 'Guinea-Bissau');
INSERT INTO {$table_name} VALUES (null, 'GY', 'Guyana');
INSERT INTO {$table_name} VALUES (null, 'HT', 'Haiti');
INSERT INTO {$table_name} VALUES (null, 'HM', 'Heard and Mc Donald Islands');
INSERT INTO {$table_name} VALUES (null, 'HN', 'Honduras');
INSERT INTO {$table_name} VALUES (null, 'HK', 'Hong Kong');
INSERT INTO {$table_name} VALUES (null, 'HU', 'Hungary');
INSERT INTO {$table_name} VALUES (null, 'IS', 'Iceland');
INSERT INTO {$table_name} VALUES (null, 'IN', 'India');
INSERT INTO {$table_name} VALUES (null, 'IM', 'Isle of Man');
INSERT INTO {$table_name} VALUES (null, 'ID', 'Indonesia');
INSERT INTO {$table_name} VALUES (null, 'IR', 'Iran (Islamic Republic of)');
INSERT INTO {$table_name} VALUES (null, 'IQ', 'Iraq');
INSERT INTO {$table_name} VALUES (null, 'IE', 'Ireland');
INSERT INTO {$table_name} VALUES (null, 'IL', 'Israel');
INSERT INTO {$table_name} VALUES (null, 'IT', 'Italy');
INSERT INTO {$table_name} VALUES (null, 'CI', 'Ivory Coast');
INSERT INTO {$table_name} VALUES (null, 'JE', 'Jersey');
INSERT INTO {$table_name} VALUES (null, 'JM', 'Jamaica');
INSERT INTO {$table_name} VALUES (null, 'JP', 'Japan');
INSERT INTO {$table_name} VALUES (null, 'JO', 'Jordan');
INSERT INTO {$table_name} VALUES (null, 'KZ', 'Kazakhstan');
INSERT INTO {$table_name} VALUES (null, 'KE', 'Kenya');
INSERT INTO {$table_name} VALUES (null, 'KI', 'Kiribati');
INSERT INTO {$table_name} VALUES (null, 'KP', 'Korea, Democratic People''s Republic of');
INSERT INTO {$table_name} VALUES (null, 'KR', 'Korea, Republic of');
INSERT INTO {$table_name} VALUES (null, 'XK', 'Kosovo');
INSERT INTO {$table_name} VALUES (null, 'KW', 'Kuwait');
INSERT INTO {$table_name} VALUES (null, 'KG', 'Kyrgyzstan');
INSERT INTO {$table_name} VALUES (null, 'LA', 'Lao People''s Democratic Republic');
INSERT INTO {$table_name} VALUES (null, 'LV', 'Latvia');
INSERT INTO {$table_name} VALUES (null, 'LB', 'Lebanon');
INSERT INTO {$table_name} VALUES (null, 'LS', 'Lesotho');
INSERT INTO {$table_name} VALUES (null, 'LR', 'Liberia');
INSERT INTO {$table_name} VALUES (null, 'LY', 'Libyan Arab Jamahiriya');
INSERT INTO {$table_name} VALUES (null, 'LI', 'Liechtenstein');
INSERT INTO {$table_name} VALUES (null, 'LT', 'Lithuania');
INSERT INTO {$table_name} VALUES (null, 'LU', 'Luxembourg');
INSERT INTO {$table_name} VALUES (null, 'MO', 'Macau');
INSERT INTO {$table_name} VALUES (null, 'MK', 'Macedonia');
INSERT INTO {$table_name} VALUES (null, 'MG', 'Madagascar');
INSERT INTO {$table_name} VALUES (null, 'MW', 'Malawi');
INSERT INTO {$table_name} VALUES (null, 'MY', 'Malaysia');
INSERT INTO {$table_name} VALUES (null, 'MV', 'Maldives');
INSERT INTO {$table_name} VALUES (null, 'ML', 'Mali');
INSERT INTO {$table_name} VALUES (null, 'MT', 'Malta');
INSERT INTO {$table_name} VALUES (null, 'MH', 'Marshall Islands');
INSERT INTO {$table_name} VALUES (null, 'MQ', 'Martinique');
INSERT INTO {$table_name} VALUES (null, 'MR', 'Mauritania');
INSERT INTO {$table_name} VALUES (null, 'MU', 'Mauritius');
INSERT INTO {$table_name} VALUES (null, 'TY', 'Mayotte');
INSERT INTO {$table_name} VALUES (null, 'MX', 'Mexico');
INSERT INTO {$table_name} VALUES (null, 'FM', 'Micronesia, Federated States of');
INSERT INTO {$table_name} VALUES (null, 'MD', 'Moldova, Republic of');
INSERT INTO {$table_name} VALUES (null, 'MC', 'Monaco');
INSERT INTO {$table_name} VALUES (null, 'MN', 'Mongolia');
INSERT INTO {$table_name} VALUES (null, 'ME', 'Montenegro');
INSERT INTO {$table_name} VALUES (null, 'MS', 'Montserrat');
INSERT INTO {$table_name} VALUES (null, 'MA', 'Morocco');
INSERT INTO {$table_name} VALUES (null, 'MZ', 'Mozambique');
INSERT INTO {$table_name} VALUES (null, 'MM', 'Myanmar');
INSERT INTO {$table_name} VALUES (null, 'NA', 'Namibia');
INSERT INTO {$table_name} VALUES (null, 'NR', 'Nauru');
INSERT INTO {$table_name} VALUES (null, 'NP', 'Nepal');
INSERT INTO {$table_name} VALUES (null, 'NL', 'Netherlands');
INSERT INTO {$table_name} VALUES (null, 'AN', 'Netherlands Antilles');
INSERT INTO {$table_name} VALUES (null, 'NC', 'New Caledonia');
INSERT INTO {$table_name} VALUES (null, 'NZ', 'New Zealand');
INSERT INTO {$table_name} VALUES (null, 'NI', 'Nicaragua');
INSERT INTO {$table_name} VALUES (null, 'NE', 'Niger');
INSERT INTO {$table_name} VALUES (null, 'NG', 'Nigeria');
INSERT INTO {$table_name} VALUES (null, 'NU', 'Niue');
INSERT INTO {$table_name} VALUES (null, 'NF', 'Norfolk Island');
INSERT INTO {$table_name} VALUES (null, 'MP', 'Northern Mariana Islands');
INSERT INTO {$table_name} VALUES (null, 'NO', 'Norway');
INSERT INTO {$table_name} VALUES (null, 'OM', 'Oman');
INSERT INTO {$table_name} VALUES (null, 'PK', 'Pakistan');
INSERT INTO {$table_name} VALUES (null, 'PW', 'Palau');
INSERT INTO {$table_name} VALUES (null, 'PS', 'Palestine');
INSERT INTO {$table_name} VALUES (null, 'PA', 'Panama');
INSERT INTO {$table_name} VALUES (null, 'PG', 'Papua New Guinea');
INSERT INTO {$table_name} VALUES (null, 'PY', 'Paraguay');
INSERT INTO {$table_name} VALUES (null, 'PE', 'Peru');
INSERT INTO {$table_name} VALUES (null, 'PH', 'Philippines');
INSERT INTO {$table_name} VALUES (null, 'PN', 'Pitcairn');
INSERT INTO {$table_name} VALUES (null, 'PL', 'Poland');
INSERT INTO {$table_name} VALUES (null, 'PT', 'Portugal');
INSERT INTO {$table_name} VALUES (null, 'PR', 'Puerto Rico');
INSERT INTO {$table_name} VALUES (null, 'QA', 'Qatar');
INSERT INTO {$table_name} VALUES (null, 'RE', 'Reunion');
INSERT INTO {$table_name} VALUES (null, 'RO', 'Romania');
INSERT INTO {$table_name} VALUES (null, 'RU', 'Russian Federation');
INSERT INTO {$table_name} VALUES (null, 'RW', 'Rwanda');
INSERT INTO {$table_name} VALUES (null, 'KN', 'Saint Kitts and Nevis');
INSERT INTO {$table_name} VALUES (null, 'LC', 'Saint Lucia');
INSERT INTO {$table_name} VALUES (null, 'VC', 'Saint Vincent and the Grenadines');
INSERT INTO {$table_name} VALUES (null, 'WS', 'Samoa');
INSERT INTO {$table_name} VALUES (null, 'SM', 'San Marino');
INSERT INTO {$table_name} VALUES (null, 'ST', 'Sao Tome and Principe');
INSERT INTO {$table_name} VALUES (null, 'SA', 'Saudi Arabia');
INSERT INTO {$table_name} VALUES (null, 'SN', 'Senegal');
INSERT INTO {$table_name} VALUES (null, 'RS', 'Serbia');
INSERT INTO {$table_name} VALUES (null, 'SC', 'Seychelles');
INSERT INTO {$table_name} VALUES (null, 'SL', 'Sierra Leone');
INSERT INTO {$table_name} VALUES (null, 'SG', 'Singapore');
INSERT INTO {$table_name} VALUES (null, 'SK', 'Slovakia');
INSERT INTO {$table_name} VALUES (null, 'SI', 'Slovenia');
INSERT INTO {$table_name} VALUES (null, 'SB', 'Solomon Islands');
INSERT INTO {$table_name} VALUES (null, 'SO', 'Somalia');
INSERT INTO {$table_name} VALUES (null, 'ZA', 'South Africa');
INSERT INTO {$table_name} VALUES (null, 'GS', 'South Georgia South Sandwich Islands');
INSERT INTO {$table_name} VALUES (null, 'ES', 'Spain');
INSERT INTO {$table_name} VALUES (null, 'LK', 'Sri Lanka');
INSERT INTO {$table_name} VALUES (null, 'SH', 'St. Helena');
INSERT INTO {$table_name} VALUES (null, 'PM', 'St. Pierre and Miquelon');
INSERT INTO {$table_name} VALUES (null, 'SD', 'Sudan');
INSERT INTO {$table_name} VALUES (null, 'SR', 'Suriname');
INSERT INTO {$table_name} VALUES (null, 'SJ', 'Svalbard and Jan Mayen Islands');
INSERT INTO {$table_name} VALUES (null, 'SZ', 'Swaziland');
INSERT INTO {$table_name} VALUES (null, 'SE', 'Sweden');
INSERT INTO {$table_name} VALUES (null, 'CH', 'Switzerland');
INSERT INTO {$table_name} VALUES (null, 'SY', 'Syrian Arab Republic');
INSERT INTO {$table_name} VALUES (null, 'TW', 'Taiwan');
INSERT INTO {$table_name} VALUES (null, 'TJ', 'Tajikistan');
INSERT INTO {$table_name} VALUES (null, 'TZ', 'Tanzania, United Republic of');
INSERT INTO {$table_name} VALUES (null, 'TH', 'Thailand');
INSERT INTO {$table_name} VALUES (null, 'TG', 'Togo');
INSERT INTO {$table_name} VALUES (null, 'TK', 'Tokelau');
INSERT INTO {$table_name} VALUES (null, 'TO', 'Tonga');
INSERT INTO {$table_name} VALUES (null, 'TT', 'Trinidad and Tobago');
INSERT INTO {$table_name} VALUES (null, 'TN', 'Tunisia');
INSERT INTO {$table_name} VALUES (null, 'TR', 'Turkey');
INSERT INTO {$table_name} VALUES (null, 'TM', 'Turkmenistan');
INSERT INTO {$table_name} VALUES (null, 'TC', 'Turks and Caicos Islands');
INSERT INTO {$table_name} VALUES (null, 'TV', 'Tuvalu');
INSERT INTO {$table_name} VALUES (null, 'UG', 'Uganda');
INSERT INTO {$table_name} VALUES (null, 'UA', 'Ukraine');
INSERT INTO {$table_name} VALUES (null, 'AE', 'United Arab Emirates');
INSERT INTO {$table_name} VALUES (null, 'GB', 'United Kingdom');
INSERT INTO {$table_name} VALUES (null, 'US', 'United States');
INSERT INTO {$table_name} VALUES (null, 'UM', 'United States minor outlying islands');
INSERT INTO {$table_name} VALUES (null, 'UY', 'Uruguay');
INSERT INTO {$table_name} VALUES (null, 'UZ', 'Uzbekistan');
INSERT INTO {$table_name} VALUES (null, 'VU', 'Vanuatu');
INSERT INTO {$table_name} VALUES (null, 'VA', 'Vatican City State');
INSERT INTO {$table_name} VALUES (null, 'VE', 'Venezuela');
INSERT INTO {$table_name} VALUES (null, 'VN', 'Vietnam');
INSERT INTO {$table_name} VALUES (null, 'VG', 'Virgin Islands (British)');
INSERT INTO {$table_name} VALUES (null, 'VI', 'Virgin Islands (U.S.)');
INSERT INTO {$table_name} VALUES (null, 'WF', 'Wallis and Futuna Islands');
INSERT INTO {$table_name} VALUES (null, 'EH', 'Western Sahara');
INSERT INTO {$table_name} VALUES (null, 'YE', 'Yemen');
INSERT INTO {$table_name} VALUES (null, 'ZR', 'Zaire');
INSERT INTO {$table_name} VALUES (null, 'ZM', 'Zambia');
INSERT INTO {$table_name} VALUES (null, 'ZW', 'Zimbabwe');
";

            dbDelta($countries_sql);
        }
    }

    private function create_table($sql) {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

new Db();