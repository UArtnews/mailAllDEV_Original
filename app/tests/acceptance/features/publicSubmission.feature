Feature:
  In order to accept submissions from the public
  As a member of the public
  I need to be able to submit an article.

Scenario:
  Given I am on "submit/Digest"
  And I enter in "event_start_date" with "08/25/2014"
  And I enter in "event_end_date" with "08/26/2014"
  And I enter in "start_time" with "8:00 AM"
  And I enter in "end_time" with "8:00 PM"
  And I enter in "location" with "This Location"
  And I enter in "name" with "Behat Test User"
  And I enter in "email" with "test@be.hat"
  And I enter in "phone" with "555-555-5555"
  And I enter in "organization" with "Behat Testing"
  And I enter in "department" with "Department of Testing"
  And I forceCheck "publish_dates"
  And I forceClick the "submitAnnouncement" button
  Then I should see "08/25/2014"
  And I should see "08/26/2014"
  And I should see "08:00:00 AM"
  And I should see "08:00:00 PM"
  And I should see "This Location"
  And I should see "Behat Test User"
  And I should see "test@be.hat"
  And I should see "Thank you for your submission"
