<?php

namespace Tests\Unit;

use LineItem;
use Person;
use TemplateFiller;
use Tests\TestCase;
use Tests\Traits\RefreshDoctrineDatabase;

class TemplateFillerTest extends TestCase
{
    use RefreshDoctrineDatabase;

    /** @test */
    public function it_can_fill_line_item_data()
    {
        $filler = new TemplateFiller();
        $lineItem = entity(LineItem::class)->make([
            'price' => 10,
        ]);
        $filler->setLineItem($lineItem);

        $this->assertSame('Foo is bar', $filler->fill('Foo is bar'));
        $this->assertSame('Foo is $10', $filler->fill('Foo is [[line_item.price]]'));
    }

    /** @test */
    public function it_can_have_custom_qualifiers()
    {
        $filler = new TemplateFiller();
        $lineItem = entity(LineItem::class)->make([
            'price' => 10,
        ]);
        $filler->setLineItem($lineItem);

        $this->assertSame('Foo is bar', $filler->fill('Foo is bar'));
        $this->assertSame('Foo is $10', $filler->fill('Foo is [[line_item.price]]'));

        $filler->setQualifiers(['<<', '>>']);
        $this->assertSame('Foo is $10', $filler->fill('Foo is <<line_item.price>>'));
    }
    
}