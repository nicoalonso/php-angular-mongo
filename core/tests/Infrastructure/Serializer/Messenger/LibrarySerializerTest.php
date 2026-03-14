<?php

namespace App\Tests\Infrastructure\Serializer\Messenger;

use App\Application\Borrow\Sanctioner\BorrowPenaltyEvent;
use App\Application\Bus\Failed\FailedDomainEvent;
use App\Infrastructure\Serializer\Messenger\LibrarySerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;

class LibrarySerializerTest extends TestCase
{
    public function testShouldFailWhenWrongDecoding(): void
    {
        $serializer = new LibrarySerializer();

        $this->expectException(MessageDecodingFailedException::class);
        $serializer->decode(['body' => '']);
    }

    public function testShouldRunWhenWrongAction(): void
    {
        $serializer = new LibrarySerializer();

        $encodedEnvelope = ['body' => json_encode(['action' => 'wrong'])];
        $envelope = $serializer->decode($encodedEnvelope);
        $message = $envelope->getMessage();

        $this->assertInstanceOf(FailedDomainEvent::class, $message);
    }

    public function testShouldFailWhenUnsupportedMessageClass(): void
    {
        $serializer = new LibrarySerializer();
        $envelope = new Envelope(new \stdClass());

        $this->expectException(MessageDecodingFailedException::class);
        $serializer->encode($envelope);
    }

    public function testShouldRunWhenEncode(): void
    {
        $event = new BorrowPenaltyEvent();
        $envelope = new Envelope($event);

        $serializer = new LibrarySerializer();

        $encoded = $serializer->encode($envelope);

        $this->assertArrayHasKey('body', $encoded);
    }
}
