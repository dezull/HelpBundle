# DezullHelpBundle

Help/Documentation bundle for Symfony2

**This bundle is far, far away from being complete. Things are likely to change (and break).**

This bundle provides interface for viewing web application's documentation or help browser. The administration backend will allow users to manage documentation content. The included frontend template use two-pane layout to show help topics & content.

## Dependencies

1. symfony/framework-bundle
2. doctrine/common
3. symfony/doctrine-bundle
4. TrsteelCkeditorBundle (Optional)


## Installation

### Using deps file

#### 1. Add the bundle to deps

    [DezullHelpBundle]
        git=git://github.com/dezull/HelpBundle.git
        target=/bundles/Dezull/Bundle/HelpBundle

#### 2. Install vendor

    $ php bin/vendors install

#### 3. Add the bundle to app/AppKernel.php

    $bundles = array(
        ...
        new Dezull\Bundle\HelpBundle\DezullHelpBundle(),
        ...
    );
    
#### 4. Add namespace to app/autoload.php

    $loader->registerNamespaces(array(
        ...
        'Dezull' => __DIR__.'/../vendor/bundles',
        ...
    ));

#### 5. Add routes. Customize to suite your application.

    _help_topic:
        type:     annotation
        pattern: /help/!{title}
        defaults: { _controller: DezullHelpBundle:Browser:index }

    _help_topic_home:
        type:     annotation
        prefix: /help
        resource: "@DezullHelpBundle/Controller/BrowserController.php"

    _help_topic_admin_category:
        type:     annotation
        prefix: /admin/help
        resource: "@DezullHelpBundle/Controller/HelpCategoryController.php"

    _help_topic_admin_topic:
        type:     annotation
        prefix: /admin/help
        resource: "@DezullHelpBundle/Controller/HelpTopicController.php"

#### 6. Install required tables into database, for example:

    CREATE TABLE help_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB;
    CREATE TABLE help_topic (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(120) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_FAD81EE212469DE2 (category_id), PRIMARY KEY(id)) ENGINE = InnoDB;
    ALTER TABLE help_topic ADD CONSTRAINT FK_FAD81EE212469DE2 FOREIGN KEY (category_id) REFERENCES help_category(id);

### Using composer.json

composer.json not yet added


## Usage

1. Use the admin interface to add new a category and topics. For example, access it via *http://example.com/admin/help/category*
2. To link to the help page, use route to DezullHelpBundle:Browser:Index with topic title as its only parameter. For example, *http://example.com/help/!How+to+use+this+web+app*
