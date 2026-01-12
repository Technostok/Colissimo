<?php

namespace LaPoste\Colissimo\Setup\Patch\Data;

use LaPoste\Colissimo\Model\Config\Source\HazmatCategories;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class HazmatPatch implements DataPatchInterface
{
    public const HAZMAT_ATTRIBUTE_CODE = 'lpc_hazmat_code';
    public const HAZMAT_ATTRIBUTE_CODE_CATEGORY = 'lpc_hazmat_code_category';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $this->addHazmatAttributeProduct($eavSetup);
        $this->addHazmatAttributeCategory($eavSetup);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Add product attribute to set hazmat category per product
     *
     * @throws \Exception
     */
    protected function addHazmatAttributeProduct($eavSetup): void
    {
        try {
            $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        } catch (LocalizedException $exception) {
            throw new \Exception($exception->getMessage() . ': ' . (Product::ENTITY));
        }

        if ($eavSetup->getAttributeId($entityTypeId, self::HAZMAT_ATTRIBUTE_CODE)) {
            return;
        }

        try {
            $eavSetup->addAttribute(
                $entityTypeId,
                self::HAZMAT_ATTRIBUTE_CODE,
                [
                    'type'                    => 'int',
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Colissimo hazmat category',
                    'input'                   => 'select',
                    'class'                   => '',
                    'source'                  => HazmatCategories::class,
                    'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => false,
                    'default'                 => 0,
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => false,
                    'unique'                  => false,
                    'apply_to'                => '',
                ]
            );
        } catch (LocalizedException|\Exception $e) {
        }
    }

    /**
     * Add product attribute to set hazmat category per product
     *
     * @throws \Exception
     */
    protected function addHazmatAttributeCategory($eavSetup): void
    {
        try {
            $entityTypeId = $eavSetup->getEntityTypeId(Category::ENTITY);
        } catch (LocalizedException $exception) {
            throw new \Exception($exception->getMessage() . ': ' . (Category::ENTITY));
        }

        if ($eavSetup->getAttributeId($entityTypeId, self::HAZMAT_ATTRIBUTE_CODE_CATEGORY)) {
            return;
        }

        try {
            $eavSetup->addAttribute(
                Category::ENTITY,
                self::HAZMAT_ATTRIBUTE_CODE_CATEGORY,
                [
                    'type'                    => 'int',
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Default Colissimo hazmat category',
                    'input'                   => 'select',
                    'class'                   => '',
                    'source'                  => HazmatCategories::class,
                    'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => false,
                    'default'                 => 0,
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => false,
                    'unique'                  => false,
                    'apply_to'                => '',
                    'group'                   => 'Content',
                ]
            );
        } catch (LocalizedException|\Exception $e) {
        }
    }
}
