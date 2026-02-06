#!/bin/bash
# API Test Suite
# Run with: bash tests/api_test.sh

API_URL="${API_URL:-http://localhost:8080/api.php}"
PASS=0
FAIL=0

echo "=========================================="
echo "    REST API TEST SUITE"
echo "=========================================="
echo ""

# Helper function to test endpoint
test_endpoint() {
    local name="$1"
    local expected_status="$2"
    shift 2
    local curl_args=("$@")
    
    echo "TEST: $name"
    echo "-------------------------------------------"
    
    response=$(curl -s -w "\n%{http_code}" "${curl_args[@]}")
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | sed '$d')
    
    echo "Response: $body"
    echo "Status: $http_code (expected: $expected_status)"
    
    if [ "$http_code" -eq "$expected_status" ]; then
        echo "✓ PASS"
        ((PASS++))
    else
        echo "✗ FAIL"
        ((FAIL++))
    fi
    echo ""
}

# Test 1: GET all products
test_endpoint \
    "GET /products - List all products" \
    200 \
    -X GET "$API_URL/products"

# Test 2: GET single product (existing)
test_endpoint \
    "GET /products/1 - Get existing product" \
    200 \
    -X GET "$API_URL/products/1"

# Test 3: GET single product (not found)
test_endpoint \
    "GET /products/999 - Product not found" \
    404 \
    -X GET "$API_URL/products/999"

# Test 4: POST create product (valid)
test_endpoint \
    "POST /products - Create valid product" \
    201 \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"name": "Monitor", "price": 350}' \
    "$API_URL/products"

# Test 5: POST create product (missing fields)
test_endpoint \
    "POST /products - Missing required fields" \
    400 \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"name": "Incomplete"}' \
    "$API_URL/products"

# Test 6: POST create product (invalid price)
test_endpoint \
    "POST /products - Invalid price (negative)" \
    400 \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"name": "Invalid", "price": -50}' \
    "$API_URL/products"

# Test 7: POST create product (invalid price type)
test_endpoint \
    "POST /products - Invalid price (string)" \
    400 \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"name": "Invalid", "price": "not-a-number"}' \
    "$API_URL/products"

# Test 8: POST create product (empty name)
test_endpoint \
    "POST /products - Empty name" \
    400 \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{"name": "", "price": 100}' \
    "$API_URL/products"

# Test 9: POST with invalid JSON
test_endpoint \
    "POST /products - Invalid JSON format" \
    400 \
    -X POST \
    -H "Content-Type: application/json" \
    -d '{invalid json}' \
    "$API_URL/products"

# Test 10: Unsupported method (returns 404 as route not found)
test_endpoint \
    "DELETE /products - Not found (unsupported)" \
    404 \
    -X DELETE "$API_URL/products"

# Summary
echo "=========================================="
echo "           TEST SUMMARY"
echo "=========================================="
echo "PASSED: $PASS"
echo "FAILED: $FAIL"
echo "TOTAL:  $((PASS + FAIL))"
echo "=========================================="

if [ $FAIL -eq 0 ]; then
    echo "✓ All tests passed!"
    exit 0
else
    echo "✗ Some tests failed"
    exit 1
fi
