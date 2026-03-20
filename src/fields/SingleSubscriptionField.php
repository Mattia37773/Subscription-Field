<?php
/**
 *  Craft Subscription Field Plugin
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace matze\craftsubscriptionfield\fields;

use Craft;
use yii\db\Schema;
use craft\fields\Dropdown;
use craft\commerce\Plugin as Commerce;

/**
 * Single Subscription Field
 *
 * @description a field to choose a Subscription or none
 * @author    Matze
 * @since     1.0.0
 */
class SingleSubscriptionField extends Dropdown
{
    /**
     * Setting if the option to chose "none Subscription" is available
     * @var bool
     */
    public bool $noneSubscription = false;

    public function init(): void  
    {
        $this->setSubscriptions();
        parent::init();
    }

    /**
    * Display Name of the Field
    * @return string
    */
    public static function displayName(): string
    {
        return Craft::t('subscription-field', 'Subscription');
    }

    /**
     * Sets the Icon of the Field Type
     * @return string
     */
    public static function icon(): string
    {
        return 'badge-dollar';
    }

    /**
     * Renders the Setting Twig Template
     *
     * @return string|null
     */
    public function getSettingsHtml(): ?string
    {
        // Renders the settings
        return Craft::$app->view->renderTemplate('subscription-field/_settings.twig', [
            'field' => $this,
        ]);
    }

    /**
     * @return string
     */
    public static function phpType(): string
    {
        return 'mixed';
    }

    /**
     * Requires that a value need to be chosen
     * @return array[]
     */
    public function getElementValidationRules(): array
    {
        // if null is chosen value can be anything
        if ($this->noneSubscription) {
            return [];
        }
        // If none is not chosen return that a value is Required
        return [
            ['required']
        ];
    }

    /***
     * Type to Store the Value in the DB
     * @return string
     */
    public static function dbType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * Sets the Values for the Field Type
     *
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    protected function setSubscriptions(): void
    {

        // Gets all Subscriptions
        $plansService = Commerce::getInstance()->getPlans();
        $plans = $plansService->getAllEnabledPlans();

        // Title
        $this->options = [
            [
                'label' =>  Craft::t('subscription-field','Choose a Subscription'),
                'value' => '',
                'disabled' => true,
            ]
        ];

        // Allows none as an Option
        if ($this->noneSubscription) {
            $this->options = [
                [
                    'label' => Craft::t('subscription-field','None'),
                    'value' => "none",
                ]
            ];
        }

        // Sets all Plans
        foreach ($plans as $plan) {
            $this->options[] = [
                'label' => $plan->name,
                'value' => $plan->handle,
            ];
        }
    }
}
