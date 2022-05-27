# 5.0.0-alpha4

### Highlighted important changes since 5.0.0-alpha3:
* Issue [#3217800](https://www.drupal.org/i/3217800):
        Updated the patch to Allow profiles to define a base/parent profile
        to work with Drupal 9.1.10

------------------------------------------------------------------------------------------------

# 5.0.0-alpha3

### Highlighted important changes since 5.0.0-alpha2:
* Issue [#3216603](https://www.drupal.org/i/3216603):
        Switched user page User Account Display mode ( My account ) 
       to use Varbase Layout Builder ~10
* Issue [#3216475](https://www.drupal.org/i/3216475):
        Added Organizational Units view and Organizational Units user block to
        list all organizational units for the current viewed user

### Added since 5.0.0-alpha2:
* Issue [#3215833](https://www.drupal.org/i/3215833):
        Added Group Book Integration custom code to Vardoc Groups
* Issue [#3216470](https://www.drupal.org/i/3216470):
        Added views argument plugin Group Membership Uid for a custom extra
        Contextual filters for views like Group Content: User is member
* Issue [#3215823](https://www.drupal.org/i/3215823):
        Added Entity Group Field module

### Changed since 5.0.0-alpha2:
* Issue [#3216853](https://www.drupal.org/i/3216853):
        Switched to CircleCI as the default automated testing platform
* Issue [#3216518](https://www.drupal.org/i/3216518):
        Removed Generic .main-content styling

### Updates since 5.0.0-alpha2:
* Updated [Varbase Core](https://www.drupal.org/project/varbase_core)
    module to [9.0.0](https://www.drupal.org/project/varbase_core/releases/9.0.0) stable release

### Fixes since 5.0.0-alpha2:
* Issue [#3216566](https://www.drupal.org/i/3216566)
       by [qusai taha](https://www.drupal.org/u/qusai-taha)
       : Fixed error when trying to add HTML block

------------------------------------------------------------------------------------------------

# 5.0.0-alpha2

### Highlighted important changes since 5.0.0-alpha1:
* Issue [#3215342](https://www.drupal.org/i/3215342):
        Added an Organizational Unit group type to add a group units in the organization

### Added since 5.0.0-alpha1:
* Issue [#3215329](https://www.drupal.org/i/3215329):
        Added Group invite, Group Membership Request, Group Notify, Group Taxonomy
        modules to Vardoc Content Collections
* Issue [#3215332](https://www.drupal.org/i/3215332):
        Added Announcement content type to let group members post announcements
        to visitors or unit members

### Changed since 5.0.0-alpha1:
* None

### Updates since 5.0.0-alpha1:
* None

### Fixes since 5.0.0-alpha1:
* None

------------------------------------------------------------------------------------------------

# 5.0.0-alpha1

### Highlighted important changes since Vardoc 4.0.1:
* Issue [#3118430](https://www.drupal.org/i/3118430):
        Started a 5.0.x branch for Vardoc and Vardoc Project to use Varbase 9 and Drupal 9
* Issue [#3123307](https://www.drupal.org/i/3123307):
        Drupal 9 readiness for the Vardoc distribution installation profile with Drupal coding
        standard and practice
* Issue [#3211942](https://www.drupal.org/i/3211942):
        Allowed Vardoc to work with Composer ~2.0
* Issue [#3211971](https://www.drupal.org/i/3211971):
        Switched Vardoc Homepage from Page Manager to Varbase Layout Builder ~10.0

### Added since Vardoc 4.0.1:
* Issue [#3212269](https://www.drupal.org/i/3212269):
        Added Redirect 403 to User Login module to Vardoc profile

### Changed since Vardoc 4.0.1:
* Issue [#3212481](https://www.drupal.org/i/3212481):
        Switched to Varbase Social Single Sign-On module and remove the Vardoc Google Authentication module
* Issue [#3212940](https://www.drupal.org/i/3212940):
        Switched to npm-asset/jquery-bar-rating from the custom repository antennaio/jquery-bar-rating
* Issue [#3211952](https://www.drupal.org/i/3211952):
        Remove _core and uuid from all configs
* Issue [#3178726](https://www.drupal.org/i/3178726)
        by [Joachim Namyslo](https://www.drupal.org/u/joachim-namyslo)
       : Remove patch from display suite issue 2975313 to no longer prevent updates

### Updates since Vardoc 4.0.1:
* Updated Varbase to 9.0.x
* Issue [#3178725](https://www.drupal.org/i/3178725)
        by [Joachim Namyslo](https://www.drupal.org/u/joachim-namyslo)
        : Increment font awesome to ~2.0

### Fixes since Vardoc 4.0.1:
* Issue [#3214596](https://www.drupal.org/i/3214596):
        Fixed responsive view for the LOGIN WITH and the option of OR at the user login page
* Issue [#3212070](https://www.drupal.org/i/3212070):
        Fixed Vardoc Features modules structure of configs and info
* Issue [#3213025](https://www.drupal.org/i/3213025):
        Fixed to Mark all updates by the update helper checklist as successful on install
* Issue [#3214710](https://www.drupal.org/i/3214710):
        Fixed Form Display and Full Content Display for the Article (Book page) content type
* Issue [#3212067](https://www.drupal.org/i/3212067):
        Fixed rename the Tools custom Vardoc feature module machine name to vardoc_tools ( Vardoc Tools )
* Issue [#3214212](https://www.drupal.org/i/3214212):
        Fixed JQuery SumoSelect Warning: constant(): Couldn't find constant CSS_PLUGINS
        in Library Discovery Parser
* Issue [#3214540](https://www.drupal.org/i/3214540):
        Fixed Alert message style at login and other pages