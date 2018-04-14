Feature: I can create and publish a post
  As a sane person
  I want to make sure that the createpost works
  So that the tests pass

  Scenario: Post exist
    Given a command with exist post title
    When executing the use case
    Then the post is not saved

  Scenario: Post saved
    Given a command with not exist post title
    When executing the use case
    Then the post is saved

  Scenario: Post saved and published
    Given a command with not exist post title and with publish true
    When executing the use case
    Then the post is saved and published