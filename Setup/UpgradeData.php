<?php 

namespace PCAPredict\Tag\Setup;
 
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

// Upgrade will only trigger if the setup_version in the module.xml is increased.
class UpgradeData implements UpgradeDataInterface {
 
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {
        
        // Take all current records and set the current version of the app as the module_version.
        // We will now fetch a record based on the last created time and not an id.
        // It was likely that with previous versions, if login/key setup failed at any point it would write a row but go back to the login screen,
        // thus subsquent attempts caused more rows to be written.
        if (version_compare($context->getVersion(), '2.0.7') < 0) {

            $tableName = $setup->getTable('pcapredict_tag_settingsdata');

            $select = $setup->getConnection()->select()->from($tableName);

            $result = $setup->getConnection()->fetchAll($select);

            foreach ($result as $row) 
            {
                // Set the new module_version column with the current of the app.
                // Because we do not know what vesion they logged in under set to the last version will have to do.
                $setup->updateTableRow($tableName, 'id', $row['id'], 'module_version', $context->getVersion());
            }
        }
    }
}