<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;
use Tenet\Generator\EntityGenerator;

class QS1Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qs1:test';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test QS1.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Application $app, Config $config)
    {
        $this->app    = $app;
        $this->config = $config;

        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // 1366753865
        //

        $sent    = date('c');
        $created = date('c');
        $user    = getenv('QS1_USER');
        $pass    = getenv('QS1_PASS');
        $nonce   = md5(uniqid());
        $digest  = base64_encode(sha1($nonce . $created . $pass, TRUE));
        $data    = <<<DATA
<?xml version="1.0" encoding="utf-8"?>
<Message version="010" release="006"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xmlns:xsd="http://www.w3.org/2001/XMLSchema"
         xmlns="http://www.ncpdp.org/schema/SCRIPT">
  <Header>
    <To Qualifier="P">0000275</To>
    <From Qualifier="D">1629397120</From>
    <MessageID>123456789PB1</MessageID>
    <SentTime>{$sent}</SentTime>
    <Security>
      <UsernameToken>
        <Username>{$user}</Username>
        <Password Type="PasswordDigest">{$digest}</Password>
        <Nonce>{$nonce}</Nonce>
        <Created>{$created}</Created>
      </UsernameToken>

      <Sender>
        <SecondaryIdentification>TestVendor1</SecondaryIdentification>
        <TertiaryIdentification>TestVendor2</TertiaryIdentification>
      </Sender>
      <Receiver>
        <TertiaryIdentification>1234567</TertiaryIdentification>
      </Receiver>

    </Security>
  </Header>
  <Body>
    <NewRx>
      <Pharmacy>
        <Identification>
          <NCPDPID>0000275</NCPDPID>
          <NPI>1043221625</NPI>
        </Identification>
        <StoreName>Pharmacy Network Services</StoreName>
        <Address>
          <AddressLine1>123 ABC Street</AddressLine1>
          <City>Lincoln</City>
          <State>WA</State>
          <ZipCode>98564</ZipCode>
        </Address>
        <CommunicationNumbers>
          <Communication>
            <Number>2065461764</Number>
            <Qualifier>WP</Qualifier>
          </Communication>
          <Communication>
            <Number>2064564000</Number>
            <Qualifier>FX</Qualifier>
          </Communication>
        </CommunicationNumbers>
      </Pharmacy>
      <Prescriber>
        <Identification>
            <NPI>1629397120</NPI>
        </Identification>
        <Name>
            <LastName>John</LastName>
            <FirstName>Fournier</FirstName>
        </Name>
        <Address>
          <AddressLine1>123 ABC Street</AddressLine1>
          <City>Lincoln</City>
          <State>WA</State>
          <ZipCode>98564</ZipCode>
        </Address>
        <CommunicationNumbers>
            <Communication>
                <Number>5555551212</Number>
                <Qualifier>TE</Qualifier>
            </Communication>
            <Communication>
                <Number>johnbfournier@gmail.com</Number>
                <Qualifier>EM</Qualifier>
            </Communication>
        </CommunicationNumbers>
      </Prescriber>
      <Patient>
        <Name>
          <LastName>Smith</LastName>
          <FirstName>Jane</FirstName>
        </Name>
        <Gender>F</Gender>
        <DateOfBirth>
          <Date>1964-07-04</Date>
        </DateOfBirth>
      </Patient>
      <MedicationPrescribed>
        <DrugDescription>Latisse 3ml 0.03%</DrugDescription>
        <DrugCoded>
          <ProductCode>00023-36136-70</ProductCode>
          <ProductCodeQualifier>ND</ProductCodeQualifier>
        </DrugCoded>
        <Quantity>
          <Value>0</Value>
          <CodeListQualifier>QS</CodeListQualifier>
          <UnitSourceCode>AC</UnitSourceCode>
          <PotencyUnitCode>C38046</PotencyUnitCode>
        </Quantity>
        <Directions>20 mg Oral Once daily</Directions>
        <Note />
        <Refills>
          <Qualifier>R</Qualifier>
          <Value>6</Value>
        </Refills>
        <Substitutions>0</Substitutions>
        <WrittenDate>
          <Date>2018-09-27</Date>
        </WrittenDate>
        <EffectiveDate>
          <Date>2018-09-27</Date>
        </EffectiveDate>
      </MedicationPrescribed>
    </NewRx>
  </Body>
</Message>
DATA;

        $context = stream_context_create([
            'http' => [
                'ignore_errors' => TRUE,
                'method'        => 'POST',
                'content'       => $data,
                'header'        => implode("\r\n", [
                    'Content-Type: application/xml',
                    'Content-Length: ' . strlen($data)
                ]),
            ],
            'ssl' => [
                'verify_peer'      => TRUE,
                'verify_peer_name' => TRUE
            ]
        ]);

        $api_url   = getenv('QS1_URL');
        $handle    = @fopen($api_url, 'rb', FALSE, $context);
        $response  = stream_get_contents($handle);
        $meta_data = stream_get_meta_data($handle);

        print_r($data);
        echo PHP_EOL;
        echo PHP_EOL;
        print_r($response);
    }
}
