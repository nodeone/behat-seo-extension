behat extension provinding specific features for testing the SEO of a website

# use

add the extension and mink goutte driver in your composer.json

    "m6web/seo-behat-extension": "dev-master",
    "behat/mink-goutte-driver": "1.0.*@dev"

## init

    ./vendor/bin/behat.yml

## create a behat.yml file

    default:
    extensions:
        Behat\MinkExtension\Extension:
            base_url: http://www.yoursite.com
            goutte: ~
        M6Web\Behat\SEOExtension\Extension:
            robots_file: %behat.paths.base%/data/robots.txt
            rules_file: %behat.paths.base%/data/rules.csv

## in FeatureContext.php

    <?php

    use Behat\Behat\Context\BehatContext;
    use Behat\CommonContexts\MinkExtraContext;
    use Behat\MinkExtension\Context\MinkContext;
    use M6Web\Behat\SEOExtension\Context\PageContext;
    use M6Web\Behat\SEOExtension\Context\RobotContext;


    /**
    * Features context.
    */
    class FeatureContext extends BehatContext
    {
        /**
         * Initializes context.
         * Every scenario gets its own context object.
         *
         * @param array $parameters context parameters (set them up through behat.yml)
        */
        public function __construct(array $parameters)
        {
            $this->useContext('mink',  new MinkContext());
            $this->useContext('mink-extra',  new MinkExtraContext());
            $this->useContext('seo-checker-robot', new RobotContext());
            $this->useContext('seo-checker-page', new PageContext());
        }
    }

## define rules in csv

TODO

# Lauch internal tests

    ./vendor/bin/phpspec