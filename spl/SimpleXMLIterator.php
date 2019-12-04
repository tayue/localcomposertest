<?php

/*** a simple xml tree ***/
$xmlstring = <<<XML
<?xml version = "1.0" encoding="UTF-8" standalone="yes"?>
<document>
  <animal>
    <category id="26">
      <species>Phascolarctidae</species>
      <type>koala</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="27">
      <species>macropod</species>
      <type>kangaroo</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="28">
      <species>diprotodon</species>
      <type>wombat</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="31">
      <species>macropod</species>
      <type>wallaby</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="21">
      <species>dromaius</species>
      <type>emu</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="22">
      <species>Apteryx</species>
      <type>kiwi</type>
      <name>Troy</name>
    </category>
  </animal>
  <animal>
    <category id="23">
      <species>kingfisher</species>
      <type>kookaburra</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="48">
      <species>monotremes</species>
      <type>platypus</type>
      <name>Bruce</name>
    </category>
  </animal>
  <animal>
    <category id="4">
      <species>arachnid</species>
      <type>funnel web</type>
      <name>Bruce</name>
      <legs>8</legs>
    </category>
  </animal>
</document>
XML;

/*** a new simpleXML iterator object ***/
try {
    /*** a new simple xml iterator ***/
    $it = new SimpleXMLIterator($xmlstring);
    /*** a new limitIterator object ***/
    foreach (new RecursiveIteratorIterator($it, 1) as $name => $data) {
        echo $name . ' -- ' . $data . '<br />';
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    /*** a new simpleXML iterator object ***/
    $sxi = new SimpleXMLIterator($xmlstring);
    /*** add a child ***/
    $sxi->addChild('animal', 'Tiger');
    /*** add an attribute with a namespace ***/
    $sxi->addAttribute('id:att1', 'good things', 'urn::test-foo');

    /*** add an attribute without a  namespace ***/
    $sxi->addAttribute('att2', 'no-ns');
    foreach ($sxi as $node) {
        foreach ($node as $k => $v) {
            echo $v->species . '<br />';
        }
    }
    echo ($sxi->saveXML());
} catch (Exception $e) {
    echo $e->getMessage();
}