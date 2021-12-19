<?php

namespace Awaresoft\SettingBundle\DataFixtures\ORM;

use Awaresoft\DoctrineBundle\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Awaresoft\SettingBundle\Entity\Setting;
use Awaresoft\SettingBundle\Entity\SettingHasField;

/**
 * Class LoadSettingData
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class LoadSettingData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // CONTACT
        $setting = new Setting();
        $setting
            ->setName('CONTACT')
            ->setEnabled(true)
            ->setInfo('Contact parameters.');
        $manager->persist($setting);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('PHONE');
        $settingField->setInfo('Global site phone number.');
        $manager->persist($settingField);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('EMAIL');
        $settingField->setInfo('Global site email address.');
        $manager->persist($settingField);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('ADDRESS');
        $settingField->setInfo('Global site address data.');
        $manager->persist($settingField);

        // SITE
        $setting = new Setting();
        $setting
            ->setName('SITE')
            ->setEnabled(true)
            ->setInfo('Site parameters.');
        $manager->persist($setting);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('COOKIES');
        $settingField->setInfo('If setting is enabled, website will display information about cookies.');
        $settingField->setEnabled(true);
        $manager->persist($settingField);

        // GOOGLE
        $setting = new Setting();
        $setting
            ->setName('GOOGLE')
            ->setEnabled(true)
            ->setInfo('Google parameters.');
        $manager->persist($setting);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('ANALYTICS');
        $settingField->setInfo('Google Analytics UA code. If setting is publicated and Google UA code is filled in value, website will collect statistics.');
        $manager->persist($settingField);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('BOT');
        $settingField->setInfo('Meta tag robots. If setting is publicated, website is open for robots.');
        $manager->persist($settingField);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('SEARCH_CONSOLE');
        $settingField->setInfo('Meta tag search console. If setting is publicated and value is added, website will works with Google Search Console. Please add to value only GSC key.');
        $manager->persist($settingField);

        $manager->flush();

        // WEBSITE (hidden)
        $setting = new Setting();
        $setting
            ->setName('WEBSITE')
            ->setEnabled(true)
            ->setHidden(true)
            ->setInfo('Website hidden parameters.');
        $manager->persist($setting);

        $settingField = new SettingHasField();
        $settingField->setSetting($setting);
        $settingField->setName('LAST_BUILD');
        $settingField->setInfo('Last update of website, format (\'YYYY-MM-DD\'.');
        $settingField->setEnabled(true);
        $manager->persist($settingField);

        $manager->flush();
    }
}
