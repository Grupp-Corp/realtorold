  <div id="thanksModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="thanksModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="thanksModalLabel">Thanks for contacting us!</h3>
    </div>
    <div class="modal-body">
      <p>
        We will get back to you within 1 business day!
      </p>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
  </div>
  <!-- Modal -->
  <div id="contactModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="contactModalLabel">Contact Us</h3>
    </div>
    <div class="modal-body" id="modalFormBody">
      <form class="modalContactForm" data-async data-target="#contactModal">
        <h2>What can we help you with?</h2>
        <div id="oopsModal" class="hidden">
          <div class="alert alert-block alert-error fade in">
            <h4 class="alert-heading">You got an error!</h4>
            <ul id="contactUsErrorList">
              <li id="nameRequired">Your name is required.</li>
              <li id="emailRequired">Please enter a valid e-mail.</li>
              <li id="messageRequired">A message should be at least 25 characters; you have entered <span id="missingChars"></span> characters.</li>
            </ul>
            <div id="mailerError"></div>
          </div>
        </div>
        <fieldset>
          <input type="text" class="span2" placeholder="Name" id="Name" name="Name" required="required" />
          <input type="text" class="span2" placeholder="Email address" id="Email" name="Email" required="required" />
          <textarea class="form-control" rows="4" cols="100" placeholder="Message" id="Message" name="Message" required></textarea>
        </fieldset>
      </form>
    </div>
    <div id="modalLoadBody" class="displayNone">
      <img src="/images/loading.gif" height="31" width="31" alt="Loading..." title="Loading..." />
    </div>
    <div class="modal-footer">
      <button id="submitModalContact" name="SubmitAutoForm" class="btn btn-primary" type="submit">Send Message</button>
      <button id="cancelModalContact" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    </div>
  </div>
  <footer>
    <img src="/images/responsive/realtor-footer-logo.png" width="442" height="155" alt="myRealtorCliq" />
  </footer>