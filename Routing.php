<?php

require_once(dirname(__FILE__) . "/bulksms/BulkSMS.php");

$bulksms = new BulkSMS();

$USERNAME = "demo";
$PASSWORD = "demo";

// Login
$bulksms->login($USERNAME, $PASSWORD);

# Print all routes
$routes = $bulksms->getRoutePricing();
foreach($routes as $route) {
    echo $route->getCountryName() . "\t" . $route->getUserRouteId() . "\t" . $route->getPrice() . "\n";
}

# Get route/pricing for specific country
$routePricing = $bulksms->getRoutePricingByCountry("Australia");
if($routePricing != null) {
    echo "Country:\t" . $routePricing->getCountryName() . "\n";
    echo "Description: \t" . $routePricing->getDescription() . "\n";
    echo "Route Id:\t" . $routePricing->getUserRouteId() . "\n";
    echo "Price:   \t" . $routePricing->getPrice() . "\n";
}

# Get route id for specific country
$country = "United Kingdom";
$routeId = $bulksms->getRouteIdByCountry($country);
echo "Route ID for {$country}: {$routeId}\n";

