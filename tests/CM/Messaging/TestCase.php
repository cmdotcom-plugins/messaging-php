<?php

namespace Test\CM\Messaging;

use CM\Messaging\Message;
use CM\Messaging\Settings\AllowedChannel;
use Faker\Factory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function getMockMessage($messageCount = 1, $valid = true)
    {
        $messages = [];
        for ($i = 0; $i < $messageCount; $i++) {
            $from                        = $this->faker->company;
            $body                        = $valid ? $this->faker->text(160) : '';
            $reference                   = $this->faker->word;
            $minimumNumberOfMessageParts = $this->faker->numberBetween(0, 7);
            $maximumNumberOfMessageParts = $minimumNumberOfMessageParts + 1;
            $allowedChannels             = [AllowedChannel::SMS, AllowedChannel::PUSH, AllowedChannel::VOICE];
            $appKey                      = $this->faker->randomKey();

            $message    = (new Message())
                ->setFrom($from)
                ->setTo(['031612345678', '031623456789'])
                ->setBody($body)
                ->setReference($reference)
                ->setMinimumNumberOfMessageParts($minimumNumberOfMessageParts)
                ->setMaximumNumberOfMessageParts($maximumNumberOfMessageParts)
                ->setAllowedChannels($allowedChannels)
                ->setAppKey($appKey)
                ->setDcs('8');
            $messages[] = $message;
        }

        return $messages;
    }

}
