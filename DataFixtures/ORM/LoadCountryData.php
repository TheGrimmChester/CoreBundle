<?php
/**
 * Copyright (c) 2010-2017, AWHSPanel by Nicolas Méloni
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     * Neither the name of AWHSPanel nor the names of its contributors
 *       may be used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace AWHS\TaskBundle\DataFixtures\ORM;

use AWHS\CoreBundle\Entity\Country;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCountryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $country_0 = new Country();
        $country_0->setIso("AD");
        $country_0->setName("ANDORRA");
        $country_0->setPrintableName("Andorra");
        $country_0->setIso3("AND");
        $country_0->setNumcode("20");
        $manager->persist($country_0);

        $country_1 = new Country();
        $country_1->setIso("AE");
        $country_1->setName("UNITED ARAB EMIRATES");
        $country_1->setPrintableName("United Arab Emirates");
        $country_1->setIso3("ARE");
        $country_1->setNumcode("784");
        $manager->persist($country_1);

        $country_2 = new Country();
        $country_2->setIso("AF");
        $country_2->setName("AFGHANISTAN");
        $country_2->setPrintableName("Afghanistan");
        $country_2->setIso3("AFG");
        $country_2->setNumcode("4");
        $manager->persist($country_2);

        $country_3 = new Country();
        $country_3->setIso("AG");
        $country_3->setName("ANTIGUA AND BARBUDA");
        $country_3->setPrintableName("Antigua and Barbuda");
        $country_3->setIso3("ATG");
        $country_3->setNumcode("28");
        $manager->persist($country_3);

        $country_4 = new Country();
        $country_4->setIso("AI");
        $country_4->setName("ANGUILLA");
        $country_4->setPrintableName("Anguilla");
        $country_4->setIso3("AIA");
        $country_4->setNumcode("660");
        $manager->persist($country_4);

        $country_5 = new Country();
        $country_5->setIso("AL");
        $country_5->setName("ALBANIA");
        $country_5->setPrintableName("Albania");
        $country_5->setIso3("ALB");
        $country_5->setNumcode("8");
        $manager->persist($country_5);

        $country_6 = new Country();
        $country_6->setIso("AM");
        $country_6->setName("ARMENIA");
        $country_6->setPrintableName("Armenia");
        $country_6->setIso3("ARM");
        $country_6->setNumcode("51");
        $manager->persist($country_6);

        $country_7 = new Country();
        $country_7->setIso("AN");
        $country_7->setName("NETHERLANDS ANTILLES");
        $country_7->setPrintableName("Netherlands Antilles");
        $country_7->setIso3("ANT");
        $country_7->setNumcode("530");
        $manager->persist($country_7);

        $country_8 = new Country();
        $country_8->setIso("AO");
        $country_8->setName("ANGOLA");
        $country_8->setPrintableName("Angola");
        $country_8->setIso3("AGO");
        $country_8->setNumcode("24");
        $manager->persist($country_8);

        $country_9 = new Country();
        $country_9->setIso("AQ");
        $country_9->setName("ANTARCTICA");
        $country_9->setPrintableName("Antarctica");
        $country_9->setIso3("");
        $country_9->setNumcode("0");
        $manager->persist($country_9);

        $country_10 = new Country();
        $country_10->setIso("AR");
        $country_10->setName("ARGENTINA");
        $country_10->setPrintableName("Argentina");
        $country_10->setIso3("ARG");
        $country_10->setNumcode("32");
        $manager->persist($country_10);

        $country_11 = new Country();
        $country_11->setIso("AS");
        $country_11->setName("AMERICAN SAMOA");
        $country_11->setPrintableName("American Samoa");
        $country_11->setIso3("ASM");
        $country_11->setNumcode("16");
        $manager->persist($country_11);

        $country_12 = new Country();
        $country_12->setIso("AT");
        $country_12->setName("AUSTRIA");
        $country_12->setPrintableName("Austria");
        $country_12->setIso3("AUT");
        $country_12->setNumcode("40");
        $manager->persist($country_12);

        $country_13 = new Country();
        $country_13->setIso("AU");
        $country_13->setName("AUSTRALIA");
        $country_13->setPrintableName("Australia");
        $country_13->setIso3("AUS");
        $country_13->setNumcode("36");
        $manager->persist($country_13);

        $country_14 = new Country();
        $country_14->setIso("AW");
        $country_14->setName("ARUBA");
        $country_14->setPrintableName("Aruba");
        $country_14->setIso3("ABW");
        $country_14->setNumcode("533");
        $manager->persist($country_14);

        $country_15 = new Country();
        $country_15->setIso("AZ");
        $country_15->setName("AZERBAIJAN");
        $country_15->setPrintableName("Azerbaijan");
        $country_15->setIso3("AZE");
        $country_15->setNumcode("31");
        $manager->persist($country_15);

        $country_16 = new Country();
        $country_16->setIso("BA");
        $country_16->setName("BOSNIA AND HERZEGOVINA");
        $country_16->setPrintableName("Bosnia and Herzegovina");
        $country_16->setIso3("BIH");
        $country_16->setNumcode("70");
        $manager->persist($country_16);

        $country_17 = new Country();
        $country_17->setIso("BB");
        $country_17->setName("BARBADOS");
        $country_17->setPrintableName("Barbados");
        $country_17->setIso3("BRB");
        $country_17->setNumcode("52");
        $manager->persist($country_17);

        $country_18 = new Country();
        $country_18->setIso("BD");
        $country_18->setName("BANGLADESH");
        $country_18->setPrintableName("Bangladesh");
        $country_18->setIso3("BGD");
        $country_18->setNumcode("50");
        $manager->persist($country_18);

        $country_19 = new Country();
        $country_19->setIso("BE");
        $country_19->setName("BELGIUM");
        $country_19->setPrintableName("Belgium");
        $country_19->setIso3("BEL");
        $country_19->setNumcode("56");
        $manager->persist($country_19);

        $country_20 = new Country();
        $country_20->setIso("BF");
        $country_20->setName("BURKINA FASO");
        $country_20->setPrintableName("Burkina Faso");
        $country_20->setIso3("BFA");
        $country_20->setNumcode("854");
        $manager->persist($country_20);

        $country_21 = new Country();
        $country_21->setIso("BG");
        $country_21->setName("BULGARIA");
        $country_21->setPrintableName("Bulgaria");
        $country_21->setIso3("BGR");
        $country_21->setNumcode("100");
        $manager->persist($country_21);

        $country_22 = new Country();
        $country_22->setIso("BH");
        $country_22->setName("BAHRAIN");
        $country_22->setPrintableName("Bahrain");
        $country_22->setIso3("BHR");
        $country_22->setNumcode("48");
        $manager->persist($country_22);

        $country_23 = new Country();
        $country_23->setIso("BI");
        $country_23->setName("BURUNDI");
        $country_23->setPrintableName("Burundi");
        $country_23->setIso3("BDI");
        $country_23->setNumcode("108");
        $manager->persist($country_23);

        $country_24 = new Country();
        $country_24->setIso("BJ");
        $country_24->setName("BENIN");
        $country_24->setPrintableName("Benin");
        $country_24->setIso3("BEN");
        $country_24->setNumcode("204");
        $manager->persist($country_24);

        $country_25 = new Country();
        $country_25->setIso("BM");
        $country_25->setName("BERMUDA");
        $country_25->setPrintableName("Bermuda");
        $country_25->setIso3("BMU");
        $country_25->setNumcode("60");
        $manager->persist($country_25);

        $country_26 = new Country();
        $country_26->setIso("BN");
        $country_26->setName("BRUNEI DARUSSALAM");
        $country_26->setPrintableName("Brunei Darussalam");
        $country_26->setIso3("BRN");
        $country_26->setNumcode("96");
        $manager->persist($country_26);

        $country_27 = new Country();
        $country_27->setIso("BO");
        $country_27->setName("BOLIVIA");
        $country_27->setPrintableName("Bolivia");
        $country_27->setIso3("BOL");
        $country_27->setNumcode("68");
        $manager->persist($country_27);

        $country_28 = new Country();
        $country_28->setIso("BR");
        $country_28->setName("BRAZIL");
        $country_28->setPrintableName("Brazil");
        $country_28->setIso3("BRA");
        $country_28->setNumcode("76");
        $manager->persist($country_28);

        $country_29 = new Country();
        $country_29->setIso("BS");
        $country_29->setName("BAHAMAS");
        $country_29->setPrintableName("Bahamas");
        $country_29->setIso3("BHS");
        $country_29->setNumcode("44");
        $manager->persist($country_29);

        $country_30 = new Country();
        $country_30->setIso("BT");
        $country_30->setName("BHUTAN");
        $country_30->setPrintableName("Bhutan");
        $country_30->setIso3("BTN");
        $country_30->setNumcode("64");
        $manager->persist($country_30);

        $country_31 = new Country();
        $country_31->setIso("BV");
        $country_31->setName("BOUVET ISLAND");
        $country_31->setPrintableName("Bouvet Island");
        $country_31->setIso3("");
        $country_31->setNumcode("0");
        $manager->persist($country_31);

        $country_32 = new Country();
        $country_32->setIso("BW");
        $country_32->setName("BOTSWANA");
        $country_32->setPrintableName("Botswana");
        $country_32->setIso3("BWA");
        $country_32->setNumcode("72");
        $manager->persist($country_32);

        $country_33 = new Country();
        $country_33->setIso("BY");
        $country_33->setName("BELARUS");
        $country_33->setPrintableName("Belarus");
        $country_33->setIso3("BLR");
        $country_33->setNumcode("112");
        $manager->persist($country_33);

        $country_34 = new Country();
        $country_34->setIso("BZ");
        $country_34->setName("BELIZE");
        $country_34->setPrintableName("Belize");
        $country_34->setIso3("BLZ");
        $country_34->setNumcode("84");
        $manager->persist($country_34);

        $country_35 = new Country();
        $country_35->setIso("CA");
        $country_35->setName("CANADA");
        $country_35->setPrintableName("Canada");
        $country_35->setIso3("CAN");
        $country_35->setNumcode("124");
        $manager->persist($country_35);

        $country_36 = new Country();
        $country_36->setIso("CC");
        $country_36->setName("COCOS (KEELING) ISLANDS");
        $country_36->setPrintableName("Cocos (Keeling) Islands");
        $country_36->setIso3("");
        $country_36->setNumcode("0");
        $manager->persist($country_36);

        $country_37 = new Country();
        $country_37->setIso("CD");
        $country_37->setName("CONGO, THE DEMOCRATIC REPUBLIC OF THE");
        $country_37->setPrintableName("Congo, the Democratic Republic of the");
        $country_37->setIso3("COD");
        $country_37->setNumcode("180");
        $manager->persist($country_37);

        $country_38 = new Country();
        $country_38->setIso("CF");
        $country_38->setName("CENTRAL AFRICAN REPUBLIC");
        $country_38->setPrintableName("Central African Republic");
        $country_38->setIso3("CAF");
        $country_38->setNumcode("140");
        $manager->persist($country_38);

        $country_39 = new Country();
        $country_39->setIso("CG");
        $country_39->setName("CONGO");
        $country_39->setPrintableName("Congo");
        $country_39->setIso3("COG");
        $country_39->setNumcode("178");
        $manager->persist($country_39);

        $country_40 = new Country();
        $country_40->setIso("CH");
        $country_40->setName("SWITZERLAND");
        $country_40->setPrintableName("Switzerland");
        $country_40->setIso3("CHE");
        $country_40->setNumcode("756");
        $manager->persist($country_40);

        $country_41 = new Country();
        $country_41->setIso("CI");
        $country_41->setName("COTE D'IVOIRE");
        $country_41->setPrintableName("Cote D'Ivoire");
        $country_41->setIso3("CIV");
        $country_41->setNumcode("384");
        $manager->persist($country_41);

        $country_42 = new Country();
        $country_42->setIso("CK");
        $country_42->setName("COOK ISLANDS");
        $country_42->setPrintableName("Cook Islands");
        $country_42->setIso3("COK");
        $country_42->setNumcode("184");
        $manager->persist($country_42);

        $country_43 = new Country();
        $country_43->setIso("CL");
        $country_43->setName("CHILE");
        $country_43->setPrintableName("Chile");
        $country_43->setIso3("CHL");
        $country_43->setNumcode("152");
        $manager->persist($country_43);

        $country_44 = new Country();
        $country_44->setIso("CM");
        $country_44->setName("CAMEROON");
        $country_44->setPrintableName("Cameroon");
        $country_44->setIso3("CMR");
        $country_44->setNumcode("120");
        $manager->persist($country_44);

        $country_45 = new Country();
        $country_45->setIso("CN");
        $country_45->setName("CHINA");
        $country_45->setPrintableName("China");
        $country_45->setIso3("CHN");
        $country_45->setNumcode("156");
        $manager->persist($country_45);

        $country_46 = new Country();
        $country_46->setIso("CO");
        $country_46->setName("COLOMBIA");
        $country_46->setPrintableName("Colombia");
        $country_46->setIso3("COL");
        $country_46->setNumcode("170");
        $manager->persist($country_46);

        $country_47 = new Country();
        $country_47->setIso("CR");
        $country_47->setName("COSTA RICA");
        $country_47->setPrintableName("Costa Rica");
        $country_47->setIso3("CRI");
        $country_47->setNumcode("188");
        $manager->persist($country_47);

        $country_48 = new Country();
        $country_48->setIso("CS");
        $country_48->setName("SERBIA AND MONTENEGRO");
        $country_48->setPrintableName("Serbia and Montenegro");
        $country_48->setIso3("");
        $country_48->setNumcode("0");
        $manager->persist($country_48);

        $country_49 = new Country();
        $country_49->setIso("CU");
        $country_49->setName("CUBA");
        $country_49->setPrintableName("Cuba");
        $country_49->setIso3("CUB");
        $country_49->setNumcode("192");
        $manager->persist($country_49);

        $country_50 = new Country();
        $country_50->setIso("CV");
        $country_50->setName("CAPE VERDE");
        $country_50->setPrintableName("Cape Verde");
        $country_50->setIso3("CPV");
        $country_50->setNumcode("132");
        $manager->persist($country_50);

        $country_51 = new Country();
        $country_51->setIso("CX");
        $country_51->setName("CHRISTMAS ISLAND");
        $country_51->setPrintableName("Christmas Island");
        $country_51->setIso3("");
        $country_51->setNumcode("0");
        $manager->persist($country_51);

        $country_52 = new Country();
        $country_52->setIso("CY");
        $country_52->setName("CYPRUS");
        $country_52->setPrintableName("Cyprus");
        $country_52->setIso3("CYP");
        $country_52->setNumcode("196");
        $manager->persist($country_52);

        $country_53 = new Country();
        $country_53->setIso("CZ");
        $country_53->setName("CZECH REPUBLIC");
        $country_53->setPrintableName("Czech Republic");
        $country_53->setIso3("CZE");
        $country_53->setNumcode("203");
        $manager->persist($country_53);

        $country_54 = new Country();
        $country_54->setIso("DE");
        $country_54->setName("GERMANY");
        $country_54->setPrintableName("Germany");
        $country_54->setIso3("DEU");
        $country_54->setNumcode("276");
        $manager->persist($country_54);

        $country_55 = new Country();
        $country_55->setIso("DJ");
        $country_55->setName("DJIBOUTI");
        $country_55->setPrintableName("Djibouti");
        $country_55->setIso3("DJI");
        $country_55->setNumcode("262");
        $manager->persist($country_55);

        $country_56 = new Country();
        $country_56->setIso("DK");
        $country_56->setName("DENMARK");
        $country_56->setPrintableName("Denmark");
        $country_56->setIso3("DNK");
        $country_56->setNumcode("208");
        $manager->persist($country_56);

        $country_57 = new Country();
        $country_57->setIso("DM");
        $country_57->setName("DOMINICA");
        $country_57->setPrintableName("Dominica");
        $country_57->setIso3("DMA");
        $country_57->setNumcode("212");
        $manager->persist($country_57);

        $country_58 = new Country();
        $country_58->setIso("DO");
        $country_58->setName("DOMINICAN REPUBLIC");
        $country_58->setPrintableName("Dominican Republic");
        $country_58->setIso3("DOM");
        $country_58->setNumcode("214");
        $manager->persist($country_58);

        $country_59 = new Country();
        $country_59->setIso("DZ");
        $country_59->setName("ALGERIA");
        $country_59->setPrintableName("Algeria");
        $country_59->setIso3("DZA");
        $country_59->setNumcode("12");
        $manager->persist($country_59);

        $country_60 = new Country();
        $country_60->setIso("EC");
        $country_60->setName("ECUADOR");
        $country_60->setPrintableName("Ecuador");
        $country_60->setIso3("ECU");
        $country_60->setNumcode("218");
        $manager->persist($country_60);

        $country_61 = new Country();
        $country_61->setIso("EE");
        $country_61->setName("ESTONIA");
        $country_61->setPrintableName("Estonia");
        $country_61->setIso3("EST");
        $country_61->setNumcode("233");
        $manager->persist($country_61);

        $country_62 = new Country();
        $country_62->setIso("EG");
        $country_62->setName("EGYPT");
        $country_62->setPrintableName("Egypt");
        $country_62->setIso3("EGY");
        $country_62->setNumcode("818");
        $manager->persist($country_62);

        $country_63 = new Country();
        $country_63->setIso("EH");
        $country_63->setName("WESTERN SAHARA");
        $country_63->setPrintableName("Western Sahara");
        $country_63->setIso3("ESH");
        $country_63->setNumcode("732");
        $manager->persist($country_63);

        $country_64 = new Country();
        $country_64->setIso("ER");
        $country_64->setName("ERITREA");
        $country_64->setPrintableName("Eritrea");
        $country_64->setIso3("ERI");
        $country_64->setNumcode("232");
        $manager->persist($country_64);

        $country_65 = new Country();
        $country_65->setIso("ES");
        $country_65->setName("SPAIN");
        $country_65->setPrintableName("Spain");
        $country_65->setIso3("ESP");
        $country_65->setNumcode("724");
        $manager->persist($country_65);

        $country_66 = new Country();
        $country_66->setIso("ET");
        $country_66->setName("ETHIOPIA");
        $country_66->setPrintableName("Ethiopia");
        $country_66->setIso3("ETH");
        $country_66->setNumcode("231");
        $manager->persist($country_66);

        $country_67 = new Country();
        $country_67->setIso("FI");
        $country_67->setName("FINLAND");
        $country_67->setPrintableName("Finland");
        $country_67->setIso3("FIN");
        $country_67->setNumcode("246");
        $manager->persist($country_67);

        $country_68 = new Country();
        $country_68->setIso("FJ");
        $country_68->setName("FIJI");
        $country_68->setPrintableName("Fiji");
        $country_68->setIso3("FJI");
        $country_68->setNumcode("242");
        $manager->persist($country_68);

        $country_69 = new Country();
        $country_69->setIso("FK");
        $country_69->setName("FALKLAND ISLANDS (MALVINAS)");
        $country_69->setPrintableName("Falkland Islands (Malvinas)");
        $country_69->setIso3("FLK");
        $country_69->setNumcode("238");
        $manager->persist($country_69);

        $country_70 = new Country();
        $country_70->setIso("FM");
        $country_70->setName("MICRONESIA, FEDERATED STATES OF");
        $country_70->setPrintableName("Micronesia, Federated States of");
        $country_70->setIso3("FSM");
        $country_70->setNumcode("583");
        $manager->persist($country_70);

        $country_71 = new Country();
        $country_71->setIso("FO");
        $country_71->setName("FAROE ISLANDS");
        $country_71->setPrintableName("Faroe Islands");
        $country_71->setIso3("FRO");
        $country_71->setNumcode("234");
        $manager->persist($country_71);

        $country_72 = new Country();
        $country_72->setIso("FR");
        $country_72->setName("FRANCE");
        $country_72->setPrintableName("France");
        $country_72->setIso3("FRA");
        $country_72->setNumcode("250");
        $manager->persist($country_72);

        $country_73 = new Country();
        $country_73->setIso("GA");
        $country_73->setName("GABON");
        $country_73->setPrintableName("Gabon");
        $country_73->setIso3("GAB");
        $country_73->setNumcode("266");
        $manager->persist($country_73);

        $country_74 = new Country();
        $country_74->setIso("GB");
        $country_74->setName("UNITED KINGDOM");
        $country_74->setPrintableName("United Kingdom");
        $country_74->setIso3("GBR");
        $country_74->setNumcode("826");
        $manager->persist($country_74);

        $country_75 = new Country();
        $country_75->setIso("GD");
        $country_75->setName("GRENADA");
        $country_75->setPrintableName("Grenada");
        $country_75->setIso3("GRD");
        $country_75->setNumcode("308");
        $manager->persist($country_75);

        $country_76 = new Country();
        $country_76->setIso("GE");
        $country_76->setName("GEORGIA");
        $country_76->setPrintableName("Georgia");
        $country_76->setIso3("GEO");
        $country_76->setNumcode("268");
        $manager->persist($country_76);

        $country_77 = new Country();
        $country_77->setIso("GF");
        $country_77->setName("FRENCH GUIANA");
        $country_77->setPrintableName("French Guiana");
        $country_77->setIso3("GUF");
        $country_77->setNumcode("254");
        $manager->persist($country_77);

        $country_78 = new Country();
        $country_78->setIso("GH");
        $country_78->setName("GHANA");
        $country_78->setPrintableName("Ghana");
        $country_78->setIso3("GHA");
        $country_78->setNumcode("288");
        $manager->persist($country_78);

        $country_79 = new Country();
        $country_79->setIso("GI");
        $country_79->setName("GIBRALTAR");
        $country_79->setPrintableName("Gibraltar");
        $country_79->setIso3("GIB");
        $country_79->setNumcode("292");
        $manager->persist($country_79);

        $country_80 = new Country();
        $country_80->setIso("GL");
        $country_80->setName("GREENLAND");
        $country_80->setPrintableName("Greenland");
        $country_80->setIso3("GRL");
        $country_80->setNumcode("304");
        $manager->persist($country_80);

        $country_81 = new Country();
        $country_81->setIso("GM");
        $country_81->setName("GAMBIA");
        $country_81->setPrintableName("Gambia");
        $country_81->setIso3("GMB");
        $country_81->setNumcode("270");
        $manager->persist($country_81);

        $country_82 = new Country();
        $country_82->setIso("GN");
        $country_82->setName("GUINEA");
        $country_82->setPrintableName("Guinea");
        $country_82->setIso3("GIN");
        $country_82->setNumcode("324");
        $manager->persist($country_82);

        $country_83 = new Country();
        $country_83->setIso("GP");
        $country_83->setName("GUADELOUPE");
        $country_83->setPrintableName("Guadeloupe");
        $country_83->setIso3("GLP");
        $country_83->setNumcode("312");
        $manager->persist($country_83);

        $country_84 = new Country();
        $country_84->setIso("GQ");
        $country_84->setName("EQUATORIAL GUINEA");
        $country_84->setPrintableName("Equatorial Guinea");
        $country_84->setIso3("GNQ");
        $country_84->setNumcode("226");
        $manager->persist($country_84);

        $country_85 = new Country();
        $country_85->setIso("GR");
        $country_85->setName("GREECE");
        $country_85->setPrintableName("Greece");
        $country_85->setIso3("GRC");
        $country_85->setNumcode("300");
        $manager->persist($country_85);

        $country_86 = new Country();
        $country_86->setIso("GS");
        $country_86->setName("SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS");
        $country_86->setPrintableName("South Georgia and the South Sandwich Islands");
        $country_86->setIso3("");
        $country_86->setNumcode("0");
        $manager->persist($country_86);

        $country_87 = new Country();
        $country_87->setIso("GT");
        $country_87->setName("GUATEMALA");
        $country_87->setPrintableName("Guatemala");
        $country_87->setIso3("GTM");
        $country_87->setNumcode("320");
        $manager->persist($country_87);

        $country_88 = new Country();
        $country_88->setIso("GU");
        $country_88->setName("GUAM");
        $country_88->setPrintableName("Guam");
        $country_88->setIso3("GUM");
        $country_88->setNumcode("316");
        $manager->persist($country_88);

        $country_89 = new Country();
        $country_89->setIso("GW");
        $country_89->setName("GUINEA-BISSAU");
        $country_89->setPrintableName("Guinea-Bissau");
        $country_89->setIso3("GNB");
        $country_89->setNumcode("624");
        $manager->persist($country_89);

        $country_90 = new Country();
        $country_90->setIso("GY");
        $country_90->setName("GUYANA");
        $country_90->setPrintableName("Guyana");
        $country_90->setIso3("GUY");
        $country_90->setNumcode("328");
        $manager->persist($country_90);

        $country_91 = new Country();
        $country_91->setIso("HK");
        $country_91->setName("HONG KONG");
        $country_91->setPrintableName("Hong Kong");
        $country_91->setIso3("HKG");
        $country_91->setNumcode("344");
        $manager->persist($country_91);

        $country_92 = new Country();
        $country_92->setIso("HM");
        $country_92->setName("HEARD ISLAND AND MCDONALD ISLANDS");
        $country_92->setPrintableName("Heard Island and Mcdonald Islands");
        $country_92->setIso3("");
        $country_92->setNumcode("0");
        $manager->persist($country_92);

        $country_93 = new Country();
        $country_93->setIso("HN");
        $country_93->setName("HONDURAS");
        $country_93->setPrintableName("Honduras");
        $country_93->setIso3("HND");
        $country_93->setNumcode("340");
        $manager->persist($country_93);

        $country_94 = new Country();
        $country_94->setIso("HR");
        $country_94->setName("CROATIA");
        $country_94->setPrintableName("Croatia");
        $country_94->setIso3("HRV");
        $country_94->setNumcode("191");
        $manager->persist($country_94);

        $country_95 = new Country();
        $country_95->setIso("HT");
        $country_95->setName("HAITI");
        $country_95->setPrintableName("Haiti");
        $country_95->setIso3("HTI");
        $country_95->setNumcode("332");
        $manager->persist($country_95);

        $country_96 = new Country();
        $country_96->setIso("HU");
        $country_96->setName("HUNGARY");
        $country_96->setPrintableName("Hungary");
        $country_96->setIso3("HUN");
        $country_96->setNumcode("348");
        $manager->persist($country_96);

        $country_97 = new Country();
        $country_97->setIso("ID");
        $country_97->setName("INDONESIA");
        $country_97->setPrintableName("Indonesia");
        $country_97->setIso3("IDN");
        $country_97->setNumcode("360");
        $manager->persist($country_97);

        $country_98 = new Country();
        $country_98->setIso("IE");
        $country_98->setName("IRELAND");
        $country_98->setPrintableName("Ireland");
        $country_98->setIso3("IRL");
        $country_98->setNumcode("372");
        $manager->persist($country_98);

        $country_99 = new Country();
        $country_99->setIso("IL");
        $country_99->setName("ISRAEL");
        $country_99->setPrintableName("Israel");
        $country_99->setIso3("ISR");
        $country_99->setNumcode("376");
        $manager->persist($country_99);

        $country_100 = new Country();
        $country_100->setIso("IN");
        $country_100->setName("INDIA");
        $country_100->setPrintableName("India");
        $country_100->setIso3("IND");
        $country_100->setNumcode("356");
        $manager->persist($country_100);

        $country_101 = new Country();
        $country_101->setIso("IO");
        $country_101->setName("BRITISH INDIAN OCEAN TERRITORY");
        $country_101->setPrintableName("British Indian Ocean Territory");
        $country_101->setIso3("");
        $country_101->setNumcode("0");
        $manager->persist($country_101);

        $country_102 = new Country();
        $country_102->setIso("IQ");
        $country_102->setName("IRAQ");
        $country_102->setPrintableName("Iraq");
        $country_102->setIso3("IRQ");
        $country_102->setNumcode("368");
        $manager->persist($country_102);

        $country_103 = new Country();
        $country_103->setIso("IR");
        $country_103->setName("IRAN, ISLAMIC REPUBLIC OF");
        $country_103->setPrintableName("Iran, Islamic Republic of");
        $country_103->setIso3("IRN");
        $country_103->setNumcode("364");
        $manager->persist($country_103);

        $country_104 = new Country();
        $country_104->setIso("IS");
        $country_104->setName("ICELAND");
        $country_104->setPrintableName("Iceland");
        $country_104->setIso3("ISL");
        $country_104->setNumcode("352");
        $manager->persist($country_104);

        $country_105 = new Country();
        $country_105->setIso("IT");
        $country_105->setName("ITALY");
        $country_105->setPrintableName("Italy");
        $country_105->setIso3("ITA");
        $country_105->setNumcode("380");
        $manager->persist($country_105);

        $country_106 = new Country();
        $country_106->setIso("JM");
        $country_106->setName("JAMAICA");
        $country_106->setPrintableName("Jamaica");
        $country_106->setIso3("JAM");
        $country_106->setNumcode("388");
        $manager->persist($country_106);

        $country_107 = new Country();
        $country_107->setIso("JO");
        $country_107->setName("JORDAN");
        $country_107->setPrintableName("Jordan");
        $country_107->setIso3("JOR");
        $country_107->setNumcode("400");
        $manager->persist($country_107);

        $country_108 = new Country();
        $country_108->setIso("JP");
        $country_108->setName("JAPAN");
        $country_108->setPrintableName("Japan");
        $country_108->setIso3("JPN");
        $country_108->setNumcode("392");
        $manager->persist($country_108);

        $country_109 = new Country();
        $country_109->setIso("KE");
        $country_109->setName("KENYA");
        $country_109->setPrintableName("Kenya");
        $country_109->setIso3("KEN");
        $country_109->setNumcode("404");
        $manager->persist($country_109);

        $country_110 = new Country();
        $country_110->setIso("KG");
        $country_110->setName("KYRGYZSTAN");
        $country_110->setPrintableName("Kyrgyzstan");
        $country_110->setIso3("KGZ");
        $country_110->setNumcode("417");
        $manager->persist($country_110);

        $country_111 = new Country();
        $country_111->setIso("KH");
        $country_111->setName("CAMBODIA");
        $country_111->setPrintableName("Cambodia");
        $country_111->setIso3("KHM");
        $country_111->setNumcode("116");
        $manager->persist($country_111);

        $country_112 = new Country();
        $country_112->setIso("KI");
        $country_112->setName("KIRIBATI");
        $country_112->setPrintableName("Kiribati");
        $country_112->setIso3("KIR");
        $country_112->setNumcode("296");
        $manager->persist($country_112);

        $country_113 = new Country();
        $country_113->setIso("KM");
        $country_113->setName("COMOROS");
        $country_113->setPrintableName("Comoros");
        $country_113->setIso3("COM");
        $country_113->setNumcode("174");
        $manager->persist($country_113);

        $country_114 = new Country();
        $country_114->setIso("KN");
        $country_114->setName("SAINT KITTS AND NEVIS");
        $country_114->setPrintableName("Saint Kitts and Nevis");
        $country_114->setIso3("KNA");
        $country_114->setNumcode("659");
        $manager->persist($country_114);

        $country_115 = new Country();
        $country_115->setIso("KP");
        $country_115->setName("KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF");
        $country_115->setPrintableName("Korea, Democratic People's Republic of");
        $country_115->setIso3("PRK");
        $country_115->setNumcode("408");
        $manager->persist($country_115);

        $country_116 = new Country();
        $country_116->setIso("KR");
        $country_116->setName("KOREA, REPUBLIC OF");
        $country_116->setPrintableName("Korea, Republic of");
        $country_116->setIso3("KOR");
        $country_116->setNumcode("410");
        $manager->persist($country_116);

        $country_117 = new Country();
        $country_117->setIso("KW");
        $country_117->setName("KUWAIT");
        $country_117->setPrintableName("Kuwait");
        $country_117->setIso3("KWT");
        $country_117->setNumcode("414");
        $manager->persist($country_117);

        $country_118 = new Country();
        $country_118->setIso("KY");
        $country_118->setName("CAYMAN ISLANDS");
        $country_118->setPrintableName("Cayman Islands");
        $country_118->setIso3("CYM");
        $country_118->setNumcode("136");
        $manager->persist($country_118);

        $country_119 = new Country();
        $country_119->setIso("KZ");
        $country_119->setName("KAZAKHSTAN");
        $country_119->setPrintableName("Kazakhstan");
        $country_119->setIso3("KAZ");
        $country_119->setNumcode("398");
        $manager->persist($country_119);

        $country_120 = new Country();
        $country_120->setIso("LA");
        $country_120->setName("LAO PEOPLE'S DEMOCRATIC REPUBLIC");
        $country_120->setPrintableName("Lao People's Democratic Republic");
        $country_120->setIso3("LAO");
        $country_120->setNumcode("418");
        $manager->persist($country_120);

        $country_121 = new Country();
        $country_121->setIso("LB");
        $country_121->setName("LEBANON");
        $country_121->setPrintableName("Lebanon");
        $country_121->setIso3("LBN");
        $country_121->setNumcode("422");
        $manager->persist($country_121);

        $country_122 = new Country();
        $country_122->setIso("LC");
        $country_122->setName("SAINT LUCIA");
        $country_122->setPrintableName("Saint Lucia");
        $country_122->setIso3("LCA");
        $country_122->setNumcode("662");
        $manager->persist($country_122);

        $country_123 = new Country();
        $country_123->setIso("LI");
        $country_123->setName("LIECHTENSTEIN");
        $country_123->setPrintableName("Liechtenstein");
        $country_123->setIso3("LIE");
        $country_123->setNumcode("438");
        $manager->persist($country_123);

        $country_124 = new Country();
        $country_124->setIso("LK");
        $country_124->setName("SRI LANKA");
        $country_124->setPrintableName("Sri Lanka");
        $country_124->setIso3("LKA");
        $country_124->setNumcode("144");
        $manager->persist($country_124);

        $country_125 = new Country();
        $country_125->setIso("LR");
        $country_125->setName("LIBERIA");
        $country_125->setPrintableName("Liberia");
        $country_125->setIso3("LBR");
        $country_125->setNumcode("430");
        $manager->persist($country_125);

        $country_126 = new Country();
        $country_126->setIso("LS");
        $country_126->setName("LESOTHO");
        $country_126->setPrintableName("Lesotho");
        $country_126->setIso3("LSO");
        $country_126->setNumcode("426");
        $manager->persist($country_126);

        $country_127 = new Country();
        $country_127->setIso("LT");
        $country_127->setName("LITHUANIA");
        $country_127->setPrintableName("Lithuania");
        $country_127->setIso3("LTU");
        $country_127->setNumcode("440");
        $manager->persist($country_127);

        $country_128 = new Country();
        $country_128->setIso("LU");
        $country_128->setName("LUXEMBOURG");
        $country_128->setPrintableName("Luxembourg");
        $country_128->setIso3("LUX");
        $country_128->setNumcode("442");
        $manager->persist($country_128);

        $country_129 = new Country();
        $country_129->setIso("LV");
        $country_129->setName("LATVIA");
        $country_129->setPrintableName("Latvia");
        $country_129->setIso3("LVA");
        $country_129->setNumcode("428");
        $manager->persist($country_129);

        $country_130 = new Country();
        $country_130->setIso("LY");
        $country_130->setName("LIBYAN ARAB JAMAHIRIYA");
        $country_130->setPrintableName("Libyan Arab Jamahiriya");
        $country_130->setIso3("LBY");
        $country_130->setNumcode("434");
        $manager->persist($country_130);

        $country_131 = new Country();
        $country_131->setIso("MA");
        $country_131->setName("MOROCCO");
        $country_131->setPrintableName("Morocco");
        $country_131->setIso3("MAR");
        $country_131->setNumcode("504");
        $manager->persist($country_131);

        $country_132 = new Country();
        $country_132->setIso("MC");
        $country_132->setName("MONACO");
        $country_132->setPrintableName("Monaco");
        $country_132->setIso3("MCO");
        $country_132->setNumcode("492");
        $manager->persist($country_132);

        $country_133 = new Country();
        $country_133->setIso("MD");
        $country_133->setName("MOLDOVA, REPUBLIC OF");
        $country_133->setPrintableName("Moldova, Republic of");
        $country_133->setIso3("MDA");
        $country_133->setNumcode("498");
        $manager->persist($country_133);

        $country_134 = new Country();
        $country_134->setIso("MG");
        $country_134->setName("MADAGASCAR");
        $country_134->setPrintableName("Madagascar");
        $country_134->setIso3("MDG");
        $country_134->setNumcode("450");
        $manager->persist($country_134);

        $country_135 = new Country();
        $country_135->setIso("MH");
        $country_135->setName("MARSHALL ISLANDS");
        $country_135->setPrintableName("Marshall Islands");
        $country_135->setIso3("MHL");
        $country_135->setNumcode("584");
        $manager->persist($country_135);

        $country_136 = new Country();
        $country_136->setIso("MK");
        $country_136->setName("MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF");
        $country_136->setPrintableName("Macedonia, the Former Yugoslav Republic of");
        $country_136->setIso3("MKD");
        $country_136->setNumcode("807");
        $manager->persist($country_136);

        $country_137 = new Country();
        $country_137->setIso("ML");
        $country_137->setName("MALI");
        $country_137->setPrintableName("Mali");
        $country_137->setIso3("MLI");
        $country_137->setNumcode("466");
        $manager->persist($country_137);

        $country_138 = new Country();
        $country_138->setIso("MM");
        $country_138->setName("MYANMAR");
        $country_138->setPrintableName("Myanmar");
        $country_138->setIso3("MMR");
        $country_138->setNumcode("104");
        $manager->persist($country_138);

        $country_139 = new Country();
        $country_139->setIso("MN");
        $country_139->setName("MONGOLIA");
        $country_139->setPrintableName("Mongolia");
        $country_139->setIso3("MNG");
        $country_139->setNumcode("496");
        $manager->persist($country_139);

        $country_140 = new Country();
        $country_140->setIso("MO");
        $country_140->setName("MACAO");
        $country_140->setPrintableName("Macao");
        $country_140->setIso3("MAC");
        $country_140->setNumcode("446");
        $manager->persist($country_140);

        $country_141 = new Country();
        $country_141->setIso("MP");
        $country_141->setName("NORTHERN MARIANA ISLANDS");
        $country_141->setPrintableName("Northern Mariana Islands");
        $country_141->setIso3("MNP");
        $country_141->setNumcode("580");
        $manager->persist($country_141);

        $country_142 = new Country();
        $country_142->setIso("MQ");
        $country_142->setName("MARTINIQUE");
        $country_142->setPrintableName("Martinique");
        $country_142->setIso3("MTQ");
        $country_142->setNumcode("474");
        $manager->persist($country_142);

        $country_143 = new Country();
        $country_143->setIso("MR");
        $country_143->setName("MAURITANIA");
        $country_143->setPrintableName("Mauritania");
        $country_143->setIso3("MRT");
        $country_143->setNumcode("478");
        $manager->persist($country_143);

        $country_144 = new Country();
        $country_144->setIso("MS");
        $country_144->setName("MONTSERRAT");
        $country_144->setPrintableName("Montserrat");
        $country_144->setIso3("MSR");
        $country_144->setNumcode("500");
        $manager->persist($country_144);

        $country_145 = new Country();
        $country_145->setIso("MT");
        $country_145->setName("MALTA");
        $country_145->setPrintableName("Malta");
        $country_145->setIso3("MLT");
        $country_145->setNumcode("470");
        $manager->persist($country_145);

        $country_146 = new Country();
        $country_146->setIso("MU");
        $country_146->setName("MAURITIUS");
        $country_146->setPrintableName("Mauritius");
        $country_146->setIso3("MUS");
        $country_146->setNumcode("480");
        $manager->persist($country_146);

        $country_147 = new Country();
        $country_147->setIso("MV");
        $country_147->setName("MALDIVES");
        $country_147->setPrintableName("Maldives");
        $country_147->setIso3("MDV");
        $country_147->setNumcode("462");
        $manager->persist($country_147);

        $country_148 = new Country();
        $country_148->setIso("MW");
        $country_148->setName("MALAWI");
        $country_148->setPrintableName("Malawi");
        $country_148->setIso3("MWI");
        $country_148->setNumcode("454");
        $manager->persist($country_148);

        $country_149 = new Country();
        $country_149->setIso("MX");
        $country_149->setName("MEXICO");
        $country_149->setPrintableName("Mexico");
        $country_149->setIso3("MEX");
        $country_149->setNumcode("484");
        $manager->persist($country_149);

        $country_150 = new Country();
        $country_150->setIso("MY");
        $country_150->setName("MALAYSIA");
        $country_150->setPrintableName("Malaysia");
        $country_150->setIso3("MYS");
        $country_150->setNumcode("458");
        $manager->persist($country_150);

        $country_151 = new Country();
        $country_151->setIso("MZ");
        $country_151->setName("MOZAMBIQUE");
        $country_151->setPrintableName("Mozambique");
        $country_151->setIso3("MOZ");
        $country_151->setNumcode("508");
        $manager->persist($country_151);

        $country_152 = new Country();
        $country_152->setIso("NA");
        $country_152->setName("NAMIBIA");
        $country_152->setPrintableName("Namibia");
        $country_152->setIso3("NAM");
        $country_152->setNumcode("516");
        $manager->persist($country_152);

        $country_153 = new Country();
        $country_153->setIso("NC");
        $country_153->setName("NEW CALEDONIA");
        $country_153->setPrintableName("New Caledonia");
        $country_153->setIso3("NCL");
        $country_153->setNumcode("540");
        $manager->persist($country_153);

        $country_154 = new Country();
        $country_154->setIso("NE");
        $country_154->setName("NIGER");
        $country_154->setPrintableName("Niger");
        $country_154->setIso3("NER");
        $country_154->setNumcode("562");
        $manager->persist($country_154);

        $country_155 = new Country();
        $country_155->setIso("NF");
        $country_155->setName("NORFOLK ISLAND");
        $country_155->setPrintableName("Norfolk Island");
        $country_155->setIso3("NFK");
        $country_155->setNumcode("574");
        $manager->persist($country_155);

        $country_156 = new Country();
        $country_156->setIso("NG");
        $country_156->setName("NIGERIA");
        $country_156->setPrintableName("Nigeria");
        $country_156->setIso3("NGA");
        $country_156->setNumcode("566");
        $manager->persist($country_156);

        $country_157 = new Country();
        $country_157->setIso("NI");
        $country_157->setName("NICARAGUA");
        $country_157->setPrintableName("Nicaragua");
        $country_157->setIso3("NIC");
        $country_157->setNumcode("558");
        $manager->persist($country_157);

        $country_158 = new Country();
        $country_158->setIso("NL");
        $country_158->setName("NETHERLANDS");
        $country_158->setPrintableName("Netherlands");
        $country_158->setIso3("NLD");
        $country_158->setNumcode("528");
        $manager->persist($country_158);

        $country_159 = new Country();
        $country_159->setIso("NO");
        $country_159->setName("NORWAY");
        $country_159->setPrintableName("Norway");
        $country_159->setIso3("NOR");
        $country_159->setNumcode("578");
        $manager->persist($country_159);

        $country_160 = new Country();
        $country_160->setIso("NP");
        $country_160->setName("NEPAL");
        $country_160->setPrintableName("Nepal");
        $country_160->setIso3("NPL");
        $country_160->setNumcode("524");
        $manager->persist($country_160);

        $country_161 = new Country();
        $country_161->setIso("NR");
        $country_161->setName("NAURU");
        $country_161->setPrintableName("Nauru");
        $country_161->setIso3("NRU");
        $country_161->setNumcode("520");
        $manager->persist($country_161);

        $country_162 = new Country();
        $country_162->setIso("NU");
        $country_162->setName("NIUE");
        $country_162->setPrintableName("Niue");
        $country_162->setIso3("NIU");
        $country_162->setNumcode("570");
        $manager->persist($country_162);

        $country_163 = new Country();
        $country_163->setIso("NZ");
        $country_163->setName("NEW ZEALAND");
        $country_163->setPrintableName("New Zealand");
        $country_163->setIso3("NZL");
        $country_163->setNumcode("554");
        $manager->persist($country_163);

        $country_164 = new Country();
        $country_164->setIso("OM");
        $country_164->setName("OMAN");
        $country_164->setPrintableName("Oman");
        $country_164->setIso3("OMN");
        $country_164->setNumcode("512");
        $manager->persist($country_164);

        $country_165 = new Country();
        $country_165->setIso("PA");
        $country_165->setName("PANAMA");
        $country_165->setPrintableName("Panama");
        $country_165->setIso3("PAN");
        $country_165->setNumcode("591");
        $manager->persist($country_165);

        $country_166 = new Country();
        $country_166->setIso("PE");
        $country_166->setName("PERU");
        $country_166->setPrintableName("Peru");
        $country_166->setIso3("PER");
        $country_166->setNumcode("604");
        $manager->persist($country_166);

        $country_167 = new Country();
        $country_167->setIso("PF");
        $country_167->setName("FRENCH POLYNESIA");
        $country_167->setPrintableName("French Polynesia");
        $country_167->setIso3("PYF");
        $country_167->setNumcode("258");
        $manager->persist($country_167);

        $country_168 = new Country();
        $country_168->setIso("PG");
        $country_168->setName("PAPUA NEW GUINEA");
        $country_168->setPrintableName("Papua New Guinea");
        $country_168->setIso3("PNG");
        $country_168->setNumcode("598");
        $manager->persist($country_168);

        $country_169 = new Country();
        $country_169->setIso("PH");
        $country_169->setName("PHILIPPINES");
        $country_169->setPrintableName("Philippines");
        $country_169->setIso3("PHL");
        $country_169->setNumcode("608");
        $manager->persist($country_169);

        $country_170 = new Country();
        $country_170->setIso("PK");
        $country_170->setName("PAKISTAN");
        $country_170->setPrintableName("Pakistan");
        $country_170->setIso3("PAK");
        $country_170->setNumcode("586");
        $manager->persist($country_170);

        $country_171 = new Country();
        $country_171->setIso("PL");
        $country_171->setName("POLAND");
        $country_171->setPrintableName("Poland");
        $country_171->setIso3("POL");
        $country_171->setNumcode("616");
        $manager->persist($country_171);

        $country_172 = new Country();
        $country_172->setIso("PM");
        $country_172->setName("SAINT PIERRE AND MIQUELON");
        $country_172->setPrintableName("Saint Pierre and Miquelon");
        $country_172->setIso3("SPM");
        $country_172->setNumcode("666");
        $manager->persist($country_172);

        $country_173 = new Country();
        $country_173->setIso("PN");
        $country_173->setName("PITCAIRN");
        $country_173->setPrintableName("Pitcairn");
        $country_173->setIso3("PCN");
        $country_173->setNumcode("612");
        $manager->persist($country_173);

        $country_174 = new Country();
        $country_174->setIso("PR");
        $country_174->setName("PUERTO RICO");
        $country_174->setPrintableName("Puerto Rico");
        $country_174->setIso3("PRI");
        $country_174->setNumcode("630");
        $manager->persist($country_174);

        $country_175 = new Country();
        $country_175->setIso("PS");
        $country_175->setName("PALESTINIAN TERRITORY, OCCUPIED");
        $country_175->setPrintableName("Palestinian Territory, Occupied");
        $country_175->setIso3("");
        $country_175->setNumcode("0");
        $manager->persist($country_175);

        $country_176 = new Country();
        $country_176->setIso("PT");
        $country_176->setName("PORTUGAL");
        $country_176->setPrintableName("Portugal");
        $country_176->setIso3("PRT");
        $country_176->setNumcode("620");
        $manager->persist($country_176);

        $country_177 = new Country();
        $country_177->setIso("PW");
        $country_177->setName("PALAU");
        $country_177->setPrintableName("Palau");
        $country_177->setIso3("PLW");
        $country_177->setNumcode("585");
        $manager->persist($country_177);

        $country_178 = new Country();
        $country_178->setIso("PY");
        $country_178->setName("PARAGUAY");
        $country_178->setPrintableName("Paraguay");
        $country_178->setIso3("PRY");
        $country_178->setNumcode("600");
        $manager->persist($country_178);

        $country_179 = new Country();
        $country_179->setIso("QA");
        $country_179->setName("QATAR");
        $country_179->setPrintableName("Qatar");
        $country_179->setIso3("QAT");
        $country_179->setNumcode("634");
        $manager->persist($country_179);

        $country_180 = new Country();
        $country_180->setIso("RE");
        $country_180->setName("REUNION");
        $country_180->setPrintableName("Reunion");
        $country_180->setIso3("REU");
        $country_180->setNumcode("638");
        $manager->persist($country_180);

        $country_181 = new Country();
        $country_181->setIso("RO");
        $country_181->setName("ROMANIA");
        $country_181->setPrintableName("Romania");
        $country_181->setIso3("ROM");
        $country_181->setNumcode("642");
        $manager->persist($country_181);

        $country_182 = new Country();
        $country_182->setIso("RU");
        $country_182->setName("RUSSIAN FEDERATION");
        $country_182->setPrintableName("Russian Federation");
        $country_182->setIso3("RUS");
        $country_182->setNumcode("643");
        $manager->persist($country_182);

        $country_183 = new Country();
        $country_183->setIso("RW");
        $country_183->setName("RWANDA");
        $country_183->setPrintableName("Rwanda");
        $country_183->setIso3("RWA");
        $country_183->setNumcode("646");
        $manager->persist($country_183);

        $country_184 = new Country();
        $country_184->setIso("SA");
        $country_184->setName("SAUDI ARABIA");
        $country_184->setPrintableName("Saudi Arabia");
        $country_184->setIso3("SAU");
        $country_184->setNumcode("682");
        $manager->persist($country_184);

        $country_185 = new Country();
        $country_185->setIso("SB");
        $country_185->setName("SOLOMON ISLANDS");
        $country_185->setPrintableName("Solomon Islands");
        $country_185->setIso3("SLB");
        $country_185->setNumcode("90");
        $manager->persist($country_185);

        $country_186 = new Country();
        $country_186->setIso("SC");
        $country_186->setName("SEYCHELLES");
        $country_186->setPrintableName("Seychelles");
        $country_186->setIso3("SYC");
        $country_186->setNumcode("690");
        $manager->persist($country_186);

        $country_187 = new Country();
        $country_187->setIso("SD");
        $country_187->setName("SUDAN");
        $country_187->setPrintableName("Sudan");
        $country_187->setIso3("SDN");
        $country_187->setNumcode("736");
        $manager->persist($country_187);

        $country_188 = new Country();
        $country_188->setIso("SE");
        $country_188->setName("SWEDEN");
        $country_188->setPrintableName("Sweden");
        $country_188->setIso3("SWE");
        $country_188->setNumcode("752");
        $manager->persist($country_188);

        $country_189 = new Country();
        $country_189->setIso("SG");
        $country_189->setName("SINGAPORE");
        $country_189->setPrintableName("Singapore");
        $country_189->setIso3("SGP");
        $country_189->setNumcode("702");
        $manager->persist($country_189);

        $country_190 = new Country();
        $country_190->setIso("SH");
        $country_190->setName("SAINT HELENA");
        $country_190->setPrintableName("Saint Helena");
        $country_190->setIso3("SHN");
        $country_190->setNumcode("654");
        $manager->persist($country_190);

        $country_191 = new Country();
        $country_191->setIso("SI");
        $country_191->setName("SLOVENIA");
        $country_191->setPrintableName("Slovenia");
        $country_191->setIso3("SVN");
        $country_191->setNumcode("705");
        $manager->persist($country_191);

        $country_192 = new Country();
        $country_192->setIso("SJ");
        $country_192->setName("SVALBARD AND JAN MAYEN");
        $country_192->setPrintableName("Svalbard and Jan Mayen");
        $country_192->setIso3("SJM");
        $country_192->setNumcode("744");
        $manager->persist($country_192);

        $country_193 = new Country();
        $country_193->setIso("SK");
        $country_193->setName("SLOVAKIA");
        $country_193->setPrintableName("Slovakia");
        $country_193->setIso3("SVK");
        $country_193->setNumcode("703");
        $manager->persist($country_193);

        $country_194 = new Country();
        $country_194->setIso("SL");
        $country_194->setName("SIERRA LEONE");
        $country_194->setPrintableName("Sierra Leone");
        $country_194->setIso3("SLE");
        $country_194->setNumcode("694");
        $manager->persist($country_194);

        $country_195 = new Country();
        $country_195->setIso("SM");
        $country_195->setName("SAN MARINO");
        $country_195->setPrintableName("San Marino");
        $country_195->setIso3("SMR");
        $country_195->setNumcode("674");
        $manager->persist($country_195);

        $country_196 = new Country();
        $country_196->setIso("SN");
        $country_196->setName("SENEGAL");
        $country_196->setPrintableName("Senegal");
        $country_196->setIso3("SEN");
        $country_196->setNumcode("686");
        $manager->persist($country_196);

        $country_197 = new Country();
        $country_197->setIso("SO");
        $country_197->setName("SOMALIA");
        $country_197->setPrintableName("Somalia");
        $country_197->setIso3("SOM");
        $country_197->setNumcode("706");
        $manager->persist($country_197);

        $country_198 = new Country();
        $country_198->setIso("SR");
        $country_198->setName("SURINAME");
        $country_198->setPrintableName("Suriname");
        $country_198->setIso3("SUR");
        $country_198->setNumcode("740");
        $manager->persist($country_198);

        $country_199 = new Country();
        $country_199->setIso("ST");
        $country_199->setName("SAO TOME AND PRINCIPE");
        $country_199->setPrintableName("Sao Tome and Principe");
        $country_199->setIso3("STP");
        $country_199->setNumcode("678");
        $manager->persist($country_199);

        $country_200 = new Country();
        $country_200->setIso("SV");
        $country_200->setName("EL SALVADOR");
        $country_200->setPrintableName("El Salvador");
        $country_200->setIso3("SLV");
        $country_200->setNumcode("222");
        $manager->persist($country_200);

        $country_201 = new Country();
        $country_201->setIso("SY");
        $country_201->setName("SYRIAN ARAB REPUBLIC");
        $country_201->setPrintableName("Syrian Arab Republic");
        $country_201->setIso3("SYR");
        $country_201->setNumcode("760");
        $manager->persist($country_201);

        $country_202 = new Country();
        $country_202->setIso("SZ");
        $country_202->setName("SWAZILAND");
        $country_202->setPrintableName("Swaziland");
        $country_202->setIso3("SWZ");
        $country_202->setNumcode("748");
        $manager->persist($country_202);

        $country_203 = new Country();
        $country_203->setIso("TC");
        $country_203->setName("TURKS AND CAICOS ISLANDS");
        $country_203->setPrintableName("Turks and Caicos Islands");
        $country_203->setIso3("TCA");
        $country_203->setNumcode("796");
        $manager->persist($country_203);

        $country_204 = new Country();
        $country_204->setIso("TD");
        $country_204->setName("CHAD");
        $country_204->setPrintableName("Chad");
        $country_204->setIso3("TCD");
        $country_204->setNumcode("148");
        $manager->persist($country_204);

        $country_205 = new Country();
        $country_205->setIso("TF");
        $country_205->setName("FRENCH SOUTHERN TERRITORIES");
        $country_205->setPrintableName("French Southern Territories");
        $country_205->setIso3("");
        $country_205->setNumcode("0");
        $manager->persist($country_205);

        $country_206 = new Country();
        $country_206->setIso("TG");
        $country_206->setName("TOGO");
        $country_206->setPrintableName("Togo");
        $country_206->setIso3("TGO");
        $country_206->setNumcode("768");
        $manager->persist($country_206);

        $country_207 = new Country();
        $country_207->setIso("TH");
        $country_207->setName("THAILAND");
        $country_207->setPrintableName("Thailand");
        $country_207->setIso3("THA");
        $country_207->setNumcode("764");
        $manager->persist($country_207);

        $country_208 = new Country();
        $country_208->setIso("TJ");
        $country_208->setName("TAJIKISTAN");
        $country_208->setPrintableName("Tajikistan");
        $country_208->setIso3("TJK");
        $country_208->setNumcode("762");
        $manager->persist($country_208);

        $country_209 = new Country();
        $country_209->setIso("TK");
        $country_209->setName("TOKELAU");
        $country_209->setPrintableName("Tokelau");
        $country_209->setIso3("TKL");
        $country_209->setNumcode("772");
        $manager->persist($country_209);

        $country_210 = new Country();
        $country_210->setIso("TL");
        $country_210->setName("TIMOR-LESTE");
        $country_210->setPrintableName("Timor-Leste");
        $country_210->setIso3("");
        $country_210->setNumcode("0");
        $manager->persist($country_210);

        $country_211 = new Country();
        $country_211->setIso("TM");
        $country_211->setName("TURKMENISTAN");
        $country_211->setPrintableName("Turkmenistan");
        $country_211->setIso3("TKM");
        $country_211->setNumcode("795");
        $manager->persist($country_211);

        $country_212 = new Country();
        $country_212->setIso("TN");
        $country_212->setName("TUNISIA");
        $country_212->setPrintableName("Tunisia");
        $country_212->setIso3("TUN");
        $country_212->setNumcode("788");
        $manager->persist($country_212);

        $country_213 = new Country();
        $country_213->setIso("TO");
        $country_213->setName("TONGA");
        $country_213->setPrintableName("Tonga");
        $country_213->setIso3("TON");
        $country_213->setNumcode("776");
        $manager->persist($country_213);

        $country_214 = new Country();
        $country_214->setIso("TR");
        $country_214->setName("TURKEY");
        $country_214->setPrintableName("Turkey");
        $country_214->setIso3("TUR");
        $country_214->setNumcode("792");
        $manager->persist($country_214);

        $country_215 = new Country();
        $country_215->setIso("TT");
        $country_215->setName("TRINIDAD AND TOBAGO");
        $country_215->setPrintableName("Trinidad and Tobago");
        $country_215->setIso3("TTO");
        $country_215->setNumcode("780");
        $manager->persist($country_215);

        $country_216 = new Country();
        $country_216->setIso("TV");
        $country_216->setName("TUVALU");
        $country_216->setPrintableName("Tuvalu");
        $country_216->setIso3("TUV");
        $country_216->setNumcode("798");
        $manager->persist($country_216);

        $country_217 = new Country();
        $country_217->setIso("TW");
        $country_217->setName("TAIWAN, PROVINCE OF CHINA");
        $country_217->setPrintableName("Taiwan, Province of China");
        $country_217->setIso3("TWN");
        $country_217->setNumcode("158");
        $manager->persist($country_217);

        $country_218 = new Country();
        $country_218->setIso("TZ");
        $country_218->setName("TANZANIA, UNITED REPUBLIC OF");
        $country_218->setPrintableName("Tanzania, United Republic of");
        $country_218->setIso3("TZA");
        $country_218->setNumcode("834");
        $manager->persist($country_218);

        $country_219 = new Country();
        $country_219->setIso("UA");
        $country_219->setName("UKRAINE");
        $country_219->setPrintableName("Ukraine");
        $country_219->setIso3("UKR");
        $country_219->setNumcode("804");
        $manager->persist($country_219);

        $country_220 = new Country();
        $country_220->setIso("UG");
        $country_220->setName("UGANDA");
        $country_220->setPrintableName("Uganda");
        $country_220->setIso3("UGA");
        $country_220->setNumcode("800");
        $manager->persist($country_220);

        $country_221 = new Country();
        $country_221->setIso("UM");
        $country_221->setName("UNITED STATES MINOR OUTLYING ISLANDS");
        $country_221->setPrintableName("United States Minor Outlying Islands");
        $country_221->setIso3("");
        $country_221->setNumcode("0");
        $manager->persist($country_221);

        $country_222 = new Country();
        $country_222->setIso("US");
        $country_222->setName("UNITED STATES");
        $country_222->setPrintableName("United States");
        $country_222->setIso3("USA");
        $country_222->setNumcode("840");
        $manager->persist($country_222);

        $country_223 = new Country();
        $country_223->setIso("UY");
        $country_223->setName("URUGUAY");
        $country_223->setPrintableName("Uruguay");
        $country_223->setIso3("URY");
        $country_223->setNumcode("858");
        $manager->persist($country_223);

        $country_224 = new Country();
        $country_224->setIso("UZ");
        $country_224->setName("UZBEKISTAN");
        $country_224->setPrintableName("Uzbekistan");
        $country_224->setIso3("UZB");
        $country_224->setNumcode("860");
        $manager->persist($country_224);

        $country_225 = new Country();
        $country_225->setIso("VA");
        $country_225->setName("HOLY SEE (VATICAN CITY STATE)");
        $country_225->setPrintableName("Holy See (Vatican City State)");
        $country_225->setIso3("VAT");
        $country_225->setNumcode("336");
        $manager->persist($country_225);

        $country_226 = new Country();
        $country_226->setIso("VC");
        $country_226->setName("SAINT VINCENT AND THE GRENADINES");
        $country_226->setPrintableName("Saint Vincent and the Grenadines");
        $country_226->setIso3("VCT");
        $country_226->setNumcode("670");
        $manager->persist($country_226);

        $country_227 = new Country();
        $country_227->setIso("VE");
        $country_227->setName("VENEZUELA");
        $country_227->setPrintableName("Venezuela");
        $country_227->setIso3("VEN");
        $country_227->setNumcode("862");
        $manager->persist($country_227);

        $country_228 = new Country();
        $country_228->setIso("VG");
        $country_228->setName("VIRGIN ISLANDS, BRITISH");
        $country_228->setPrintableName("Virgin Islands, British");
        $country_228->setIso3("VGB");
        $country_228->setNumcode("92");
        $manager->persist($country_228);

        $country_229 = new Country();
        $country_229->setIso("VI");
        $country_229->setName("VIRGIN ISLANDS, U.S.");
        $country_229->setPrintableName("Virgin Islands, U.s.");
        $country_229->setIso3("VIR");
        $country_229->setNumcode("850");
        $manager->persist($country_229);

        $country_230 = new Country();
        $country_230->setIso("VN");
        $country_230->setName("VIET NAM");
        $country_230->setPrintableName("Viet Nam");
        $country_230->setIso3("VNM");
        $country_230->setNumcode("704");
        $manager->persist($country_230);

        $country_231 = new Country();
        $country_231->setIso("VU");
        $country_231->setName("VANUATU");
        $country_231->setPrintableName("Vanuatu");
        $country_231->setIso3("VUT");
        $country_231->setNumcode("548");
        $manager->persist($country_231);

        $country_232 = new Country();
        $country_232->setIso("WF");
        $country_232->setName("WALLIS AND FUTUNA");
        $country_232->setPrintableName("Wallis and Futuna");
        $country_232->setIso3("WLF");
        $country_232->setNumcode("876");
        $manager->persist($country_232);

        $country_233 = new Country();
        $country_233->setIso("WS");
        $country_233->setName("SAMOA");
        $country_233->setPrintableName("Samoa");
        $country_233->setIso3("WSM");
        $country_233->setNumcode("882");
        $manager->persist($country_233);

        $country_234 = new Country();
        $country_234->setIso("YE");
        $country_234->setName("YEMEN");
        $country_234->setPrintableName("Yemen");
        $country_234->setIso3("YEM");
        $country_234->setNumcode("887");
        $manager->persist($country_234);

        $country_235 = new Country();
        $country_235->setIso("YT");
        $country_235->setName("MAYOTTE");
        $country_235->setPrintableName("Mayotte");
        $country_235->setIso3("");
        $country_235->setNumcode("0");
        $manager->persist($country_235);

        $country_236 = new Country();
        $country_236->setIso("ZA");
        $country_236->setName("SOUTH AFRICA");
        $country_236->setPrintableName("South Africa");
        $country_236->setIso3("ZAF");
        $country_236->setNumcode("710");
        $manager->persist($country_236);

        $country_237 = new Country();
        $country_237->setIso("ZM");
        $country_237->setName("ZAMBIA");
        $country_237->setPrintableName("Zambia");
        $country_237->setIso3("ZMB");
        $country_237->setNumcode("894");
        $manager->persist($country_237);

        $country_238 = new Country();
        $country_238->setIso("ZW");
        $country_238->setName("ZIMBABWE");
        $country_238->setPrintableName("Zimbabwe");
        $country_238->setIso3("ZWE");
        $country_238->setNumcode("716");
        $manager->persist($country_238);

        $manager->flush();
    }
}
