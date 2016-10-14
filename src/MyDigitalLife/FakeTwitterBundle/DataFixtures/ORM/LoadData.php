<?php

namespace MyDigitalLife\FakeTwitterBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;

class LoadData extends AbstractLoader
{

    /**
     * Returns an array of file paths to fixtures. File paths can be relatives, specified with the `@Bundlename`
     * notation or being SplFileInfo instances.
     *
     * @return string[]|\SplFileInfo[]
     */
    public function getFixtures()
    {
        return [
            '@FakeTwitterBundle/DataFixtures/ORM/tweets.yml',
            '@FakeTwitterBundle/DataFixtures/ORM/users.yml',
        ];
    }
}