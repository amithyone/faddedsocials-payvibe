-- PayVibe Gateway Setup SQL Script
-- Run this script in your database to add PayVibe payment gateway

-- Insert PayVibe gateway
INSERT INTO `gateways` (`code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `description`, `created_at`, `updated_at`) 
VALUES (
    120, 
    'PayVibe', 
    'PayVibe', 
    1, 
    '{"public_key":{"title":"Public Key","global":true,"value":"pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8"},"secret_key":{"title":"Secret Key","global":true,"value":"sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3"}}', 
    '{"NGN":{"symbol":"₦"}}', 
    0, 
    'PayVibe Payment Gateway', 
    NOW(), 
    NOW()
);

-- Insert PayVibe gateway currency
INSERT INTO `gateway_currencies` (`name`, `gateway_alias`, `currency`, `symbol`, `method_code`, `min_amount`, `max_amount`, `percent_charge`, `fixed_charge`, `rate`, `gateway_parameter`, `created_at`, `updated_at`) 
VALUES (
    'PayVibe - NGN', 
    'PayVibe', 
    'NGN', 
    '₦', 
    120, 
    100, 
    1000000, 
    1.5, 
    100, 
    1, 
    '{"public_key":"pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8","secret_key":"sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3"}', 
    NOW(), 
    NOW()
); 