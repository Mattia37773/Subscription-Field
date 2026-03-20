<?php

/**
 * Craft Subscription Field Plugin
 *
 * @author Matze
 * @copyright Matze
 * @license MIT
 */

namespace matze\craftsubscriptionfield;

use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use matze\craftsubscriptionfield\fields\MultipleSubscriptionsField;
use matze\craftsubscriptionfield\fields\SIngleSubscriptionField;
use yii\base\Event;

/**
 * SubscriptionField Class
 *
 * @description Main Plugin Class
 * @author    Matze
 * @since     1.0.0
 */
class SubscriptionField extends Plugin
{
    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSettings = false;

    /**
     * @var bool
     */
    public bool $hasCpSection = false;


    /**
     * Plugin Init Funktion
     * @return void
     */
    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();
    }

    /**
     * Registers the Field's
     * @return void
     */
    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/5.x/extend/events.html to get started)
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = SIngleSubscriptionField::class;
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = MultipleSubscriptionsField::class;
        });
    }
}
