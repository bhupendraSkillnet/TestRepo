<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use GuzzleHttp\Client;

/**
 * @method \Pyz\Zed\MerchantSalesOrder\Communication\MerchantSalesOrderCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class HandleApiCallConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    use LoggerTrait;

    /**
     * {@inheritDoc}
     * - Returns TRUE if all order items are in a correct state.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $apiResponse = $this->callDummyApi();
        $this->getLogger()->info('API Response: ' . json_encode($apiResponse));

        // If API returns a specific condition, change logic
        if (!empty($apiResponse) && isset($apiResponse['id']) && $apiResponse['id'] > 0) {
            return false; // Adjust logic based on actual requirements
        }

        return true;
    }

    private function callDummyApi(): array
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1');
            $data = json_decode($response->getBody()->getContents(), true);
            return $data ?? [];
        } catch (\Exception $e) {
            $this->getLogger()->error('API Call Failed: ' . $e->getMessage());
            return [];
        }
    }
}
