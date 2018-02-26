<?php

use Tests\ModulosTestCase;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class TButtonTest extends ModulosTestCase
{
    public function testGettersAndSeeters()
    {
        $tButton = new TButton();

        $return = $tButton->setName('Name');
        $this->assertInstanceOf(TButton::class, $return);
        $this->assertEquals($tButton->getName(), 'Name');

        $return = $tButton->setRoute('Route');
        $this->assertInstanceOf(TButton::class, $return);
        $this->assertEquals($tButton->getRoute(), 'Route');

        $return = $tButton->setIcon('Icon');
        $this->assertInstanceOf(TButton::class, $return);
        $this->assertEquals($tButton->getIcon(), 'Icon');

        $return = $tButton->setStyle('Style');
        $this->assertInstanceOf(TButton::class, $return);
        $this->assertEquals($tButton->getStyle(), 'Style');

        $return = $tButton->setParameters(['Parameters' => 'params']);
        $this->assertInstanceOf(TButton::class, $return);
        $this->assertEquals($tButton->getParameters(), ['Parameters' => 'params']);

        $this->assertEquals($tButton->getTarget(), '_self');

        $return = $tButton->setTarget('Target');
        $this->assertInstanceOf(TButton::class, $return);
        $this->assertEquals($tButton->getTarget(), 'Target');
    }
}
