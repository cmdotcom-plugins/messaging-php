<?php

namespace Test\CM\Messaging;

use CM\Messaging\Message;
use CM\Messaging\Settings\AllowedChannel;

class MessageTest extends TestCase
{

    public function testInstantiation()
    {
        $from                        = $this->faker->company;
        $body                        = $this->faker->text(160);
        $reference                   = $this->faker->word;
        $minimumNumberOfMessageParts = $this->faker->numberBetween(0, 7);
        $maximumNumberOfMessageParts = $minimumNumberOfMessageParts + 1;
        $allowedChannels             = [AllowedChannel::SMS, AllowedChannel::PUSH, AllowedChannel::VOICE];
        $appKey                      = $this->faker->randomKey();

        $message = (new Message())
            ->setFrom($from)
            ->setTo(['031612345678'])
            ->setBody($body)
            ->setReference($reference)
            ->setMinimumNumberOfMessageParts($minimumNumberOfMessageParts)
            ->setMaximumNumberOfMessageParts($maximumNumberOfMessageParts)
            ->setAllowedChannels($allowedChannels)
            ->setAppKey($appKey)
            ->setDcs('8');

        $this->assertInstanceOf('CM\Messaging\Message', $message);
        $this->assertEquals($from, $message->getFrom());
        $this->assertArraySubset(['031612345678'], $message->getTo());
        $this->assertEquals($body, $message->getBody());
        $this->assertEquals($reference, $message->getReference());
        $this->assertEquals($minimumNumberOfMessageParts, $message->getMinimumNumberOfMessageParts());
        $this->assertEquals($maximumNumberOfMessageParts, $message->getMaximumNumberOfMessageParts());
        $this->assertEquals($allowedChannels, $message->getAllowedChannels());
        $this->assertEquals($appKey, $message->getAppKey());
    }

    public function testSinglePhoneNumber()
    {
        $message = new Message();
        $message->setFrom($this->faker->company);
        $message->setTo('031612345678');
        $message->setBody($this->faker->text(160));

        $this->assertInstanceOf('CM\Messaging\Message', $message);
        $this->assertArraySubset(['031612345678'], $message->getTo());
    }

    public function testMultiplePhoneNumbers()
    {
        $message = new Message();
        $message->setFrom($this->faker->company);
        $message->setTo(['031612345678', '031623456789']);
        $message->setBody($this->faker->text(160));

        $this->assertInstanceOf('CM\Messaging\Message', $message);
        $this->assertArraySubset(['031612345678', '031623456789'], $message->getTo());
    }

    public function testSingleAllowedChannel()
    {
        $message = new Message();
        $message->setFrom($this->faker->company);
        $message->setTo(['031612345678']);
        $message->setBody($this->faker->text(160));
        $message->setAllowedChannels([AllowedChannel::SMS]);

        $this->assertInstanceOf('CM\Messaging\Message', $message);
        $this->assertArraySubset(['SMS'], $message->getAllowedChannels());

    }

    public function testMultipleAllowedChannels()
    {
        $message = new Message();
        $message->setFrom($this->faker->company);
        $message->setTo(['031612345678']);
        $message->setBody($this->faker->text(160));
        $message->setAllowedChannels([AllowedChannel::SMS, AllowedChannel::PUSH, AllowedChannel::VOICE]);

        $this->assertInstanceOf('CM\Messaging\Message', $message);
        $this->assertArraySubset($message->getAllowedChannels(), ['SMS', 'Push', 'Voice']);
    }
}