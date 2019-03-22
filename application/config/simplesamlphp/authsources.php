<?php

use Cekurte\Environment\Environment;

// Can we load these dynamically from application/config/config.php?
$base_url = Environment::get('CRM_URL', 'http://localhost:8080');
$saml2_idp_url = Environment::get('SAML2_IDP_URL');
$saml2_sp_private_key_path = Environment::get('SAML2_SP_PRIVATE_KEY_PATH');
$saml2_sp_certificate_path = Environment::get('SAML2_SP_CERTIFICATE_PATH');


$config = array(
    // This is a authentication source which handles admin authentication.
    'admin' => array(
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ),

    /* This is the name of this authentication source, and will be used to access it later. */
    'max' => array(
        'saml:SP',
        'entityID' => $base_url,
        'idp' => $saml2_idp_url,
        'privatekey' => $saml2_sp_private_key_path,
        'certificate' => $saml2_sp_certificate_path,
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
    ),
);
