Feature: Navigation Tabs
  In order to navigate this webpage
  As an editor
  I need to be able to follow these nav-bar links

Scenario: User clicks on Digest
  Given I am on "edit/Digest"
  When I follow "navbar-brand-link"
  Then I should be on "edit/Digest"

Scenario: User clicks on articles
  Given I am on "edit/Digest"
  When I follow "articles-nav-link"
  Then I should be on "edit/Digest/articles"

Scenario: User clicks on publications
  Given I am on "edit/Digest"
  When I follow "publications-nav-link"
  Then I should be on "edit/Digest/publications"

Scenario: User clicks on images
  Given I am on "edit/Digest"
  When I follow "images-nav-link"
  Then I should be on "edit/Digest/images"

Scenario: User clicks on help
  Given I am on "edit/Digest"
  When I follow "help-nav-link"
  Then I should be on "edit/Digest/help"