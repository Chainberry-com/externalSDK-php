<?php

// Simple autoloader for the SDK classes

// Core classes
require_once __DIR__ . '/Configuration.php';
require_once __DIR__ . '/ApiException.php';
require_once __DIR__ . '/ObjectSerializer.php';
require_once __DIR__ . '/HeaderSelector.php';
require_once __DIR__ . '/FormDataProcessor.php';

// Error classes
require_once __DIR__ . '/Errors/BerrySdkError.php';

// API classes
require_once __DIR__ . '/Api/AssetApi.php';
require_once __DIR__ . '/Api/DepositsApi.php';
require_once __DIR__ . '/Api/OauthApi.php';
require_once __DIR__ . '/Api/TestApi.php';
require_once __DIR__ . '/Api/HealthApi.php';
require_once __DIR__ . '/Api/PartnersApi.php';
require_once __DIR__ . '/Api/PublicKeyApi.php';
require_once __DIR__ . '/Api/WebhookApi.php';
require_once __DIR__ . '/Api/WithdrawApi.php';

// Utils classes
require_once __DIR__ . '/Utils/CryptoUtils.php';
require_once __DIR__ . '/Utils/Logger.php';
require_once __DIR__ . '/Utils/Retry.php';
require_once __DIR__ . '/Utils/Validation.php';

// Services classes
require_once __DIR__ . '/Services/DepositService.php';
require_once __DIR__ . '/Services/WithdrawService.php';

// Main SDK classes
require_once __DIR__ . '/ApiSetup.php';
require_once __DIR__ . '/BerrySdk.php';