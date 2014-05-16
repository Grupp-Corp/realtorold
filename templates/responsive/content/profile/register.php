        <div id="main">
          <form id="signUpForm" method="post" action="/regnownojs" class="bbq">
            <noscript>
              <?php
			  if (isset($_REQUEST['success'])) {
				  if ($_REQUEST['success'] == 1) {
					  echo '<div id="status">Registration Successful</div>';
				  } else {
					  echo '<div id="status">Registration Failed, see errors below:</div>';
					  if (isset($_SESSION['regerrorstring']) && $_SESSION['regerrorstring'] > '') {
					  	  echo '<div class="red font_size_12">' . $_SESSION['regerrorstring'] . '</div>';
					  } else {
						  echo '<div class="red font_size_12">An Unknown Error Occurred.</div>';
					  }
					  if (isset($_SESSION['formrequestarray']) && is_array($_SESSION['formrequestarray'])) {
					  	  $_REQUEST = $_SESSION['formrequestarray'];
					  }
				  }
			  } else {
				  $_SESSION['regerrorstring'] = '';  
			  }
			  ?>
            </noscript>
			<div id="fieldWrapper">
              <div id="data"></div>
                <fieldset id="finalize" class="step">
                    <legend>
                        <span class="font_normal_07em_black">Personal Information</span>
                        <p><span class="red">*</span> Denotes Required Field</p>
                    </legend>
                    <br />
                    <label class="registration" for="regusername">User name <span class="red">*</span></label>
                    <input class="input_field_12em" type="text"  name="regusername" id="regusername" required="required" value="<?php if (isset($_REQUEST['regusername'])) { echo $_REQUEST['regusername']; } ?>" />
                    <br/>
                    <label class="registration" for="regemail">Email <span class="red">*</span></label>
                    <input class="input_field_12em" type="text"  name="regemail" id="regemail" required="required" value="<?php if (isset($_REQUEST['regemail'])) { echo $_REQUEST['regemail']; } ?>" />
                    <br />
                    <label class="registration" for="regpassword">Password <span class="red">*</span></label>
                    <input class="input_field_12em" type="password" name="regpassword" id="regpassword" required="required" value="<?php if (isset($_REQUEST['regpassword'])) { echo $_REQUEST['regpassword']; } ?>" />
                    <div id="pwresult">
                        &nbsp;
                    </div>
                    <label class="registration" for="retypePassword">Retype password <span class="red">*</span></label>
                    <input class="input_field_12em" type="password" name="retypePassword" id="retypePassword" required="required" value="<?php if (isset($_REQUEST['retypePassword'])) { echo $_REQUEST['retypePassword']; } ?>" />
                    <br />
                    <label class="registration" for="City">City <span class="red">*</span></label>
                    <input class="input_field_12em" type="text"  name="City" id="City" required="required" value="<?php if (isset($_REQUEST['City'])) { echo $_REQUEST['City']; } ?>" />
                    <br />
                    <label class="registration" for="State">State <span class="red">*</span></label>
                    <select id="State" name="State" required="required">
                        <option value=""<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == '') { echo ' selected="selected"'; } ?>>(Select One)</option>
                        <option value="AS"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'AS') { echo ' selected="selected"'; } ?>>American Samoa</option>
                        <option value="AL"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'AL') { echo ' selected="selected"'; } ?>>Alabama</option>
                        <option value="AK"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'AK') { echo ' selected="selected"'; } ?>>Alaska</option>
                        <option value="AZ"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'AZ') { echo ' selected="selected"'; } ?>>Arizona</option>
                        <option value="AR"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'AR') { echo ' selected="selected"'; } ?>>Arkansas</option>
                        <option value="CA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'CA') { echo ' selected="selected"'; } ?>>California</option>
                        <option value="CO"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'CO') { echo ' selected="selected"'; } ?>>Colorado</option>
                        <option value="CT"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'CT') { echo ' selected="selected"'; } ?>>Connecticut</option>
                        <option value="DE"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'DE') { echo ' selected="selected"'; } ?>>Delaware</option>
                        <option value="DC"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'DC') { echo ' selected="selected"'; } ?>>District Of Columbia</option>
                        <option value="FL"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'FL') { echo ' selected="selected"'; } ?>>Florida</option>
                        <option value="GA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'GA') { echo ' selected="selected"'; } ?>>Georgia</option>
                        <option value="GU"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'GU') { echo ' selected="selected"'; } ?>>Guam</option>
                        <option value="HI"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'HI') { echo ' selected="selected"'; } ?>>Hawaii</option>
                        <option value="ID"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'ID') { echo ' selected="selected"'; } ?>>Idaho</option>
                        <option value="IL"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'IL') { echo ' selected="selected"'; } ?>>Illinois</option>
                        <option value="IN"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'IN') { echo ' selected="selected"'; } ?>>Indiana</option>
                        <option value="IA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'IA') { echo ' selected="selected"'; } ?>>Iowa</option>
                        <option value="KS"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'KS') { echo ' selected="selected"'; } ?>>Kansas</option>
                        <option value="KY"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'KY') { echo ' selected="selected"'; } ?>>Kentucky</option>
                        <option value="LA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'LA') { echo ' selected="selected"'; } ?>>Louisiana</option>
                        <option value="ME"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'ME') { echo ' selected="selected"'; } ?>>Maine</option>
                        <option value="MD"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MD') { echo ' selected="selected"'; } ?>>Maryland</option>
                        <option value="MA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MA') { echo ' selected="selected"'; } ?>>Massachusetts</option>
                        <option value="MI"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MI') { echo ' selected="selected"'; } ?>>Michigan</option>
                        <option value="MN"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MN') { echo ' selected="selected"'; } ?>>Minnesota</option>
                        <option value="MS"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MS') { echo ' selected="selected"'; } ?>>Mississippi</option>
                        <option value="MO"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MO') { echo ' selected="selected"'; } ?>>Missouri</option>
                        <option value="MT"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MT') { echo ' selected="selected"'; } ?>>Montana</option>
                        <option value="NE"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NE') { echo ' selected="selected"'; } ?>>Nebraska</option>
                        <option value="NV"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NV') { echo ' selected="selected"'; } ?>>Nevada</option>
                        <option value="NH"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NH') { echo ' selected="selected"'; } ?>>New Hampshire</option>
                        <option value="NJ"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NJ') { echo ' selected="selected"'; } ?>>New Jersey</option>
                        <option value="NM"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NM') { echo ' selected="selected"'; } ?>>New Mexico</option>
                        <option value="NY"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NY') { echo ' selected="selected"'; } ?>>New York</option>
                        <option value="NC"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'NC') { echo ' selected="selected"'; } ?>>North Carolina</option>
                        <option value="ND"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'ND') { echo ' selected="selected"'; } ?>>North Dakota</option>
                        <option value="MP"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'MP') { echo ' selected="selected"'; } ?>>Northern Mariana Islands</option>
                        <option value="OH"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'OH') { echo ' selected="selected"'; } ?>>Ohio</option>
                        <option value="OK"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'OK') { echo ' selected="selected"'; } ?>>Oklahoma</option>
                        <option value="OR"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'OR') { echo ' selected="selected"'; } ?>>Oregon</option>
                        <option value="PA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'PA') { echo ' selected="selected"'; } ?>>Pennsylvania</option>
                        <option value="PR"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'PR') { echo ' selected="selected"'; } ?>>Puerto Rico</option>
                        <option value="RI"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'RI') { echo ' selected="selected"'; } ?>>Rhode Island</option>
                        <option value="SC"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'SC') { echo ' selected="selected"'; } ?>>South Carolina</option>
                        <option value="SD"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'SD') { echo ' selected="selected"'; } ?>>South Dakota</option>
                        <option value="TN"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'TN') { echo ' selected="selected"'; } ?>>Tennessee</option>
                        <option value="TX"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'TX') { echo ' selected="selected"'; } ?>>Texas</option>
                        <option value="UM"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'UM') { echo ' selected="selected"'; } ?>>United States Minor Outlying Islands</option>
                        <option value="UT"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'UT') { echo ' selected="selected"'; } ?>>Utah</option>
                        <option value="VT"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'VT') { echo ' selected="selected"'; } ?>>Vermont</option>
                        <option value="VI"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'VI') { echo ' selected="selected"'; } ?>>Virgin Islands</option>
                        <option value="VA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'VA') { echo ' selected="selected"'; } ?>>Virginia</option>
                        <option value="WA"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'WA') { echo ' selected="selected"'; } ?>>Washington</option>
                        <option value="WV"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'WV') { echo ' selected="selected"'; } ?>>West Virginia</option>
                        <option value="WI"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'WI') { echo ' selected="selected"'; } ?>>Wisconsin</option>
                        <option value="WY"<?php if (isset($_REQUEST['State']) && $_REQUEST['State'] == 'WY') { echo ' selected="selected"'; } ?>>Wyoming</option>
                    </select>
                    <br />
                    <label class="registration" for="ZIP">ZIP <span class="red">*</span></label>
                    <input class="input_field_12em" type="text"  pattern="[0-9]*" maxlength="5" name="ZIP" id="ZIP" required="required" value="<?php if (isset($_REQUEST['ZIP'])) { echo $_REQUEST['ZIP']; } ?>" />
                    <br />
                    <label class="registration" for="captcha">Captcha <span class="red">*</span></label>
                    <div id="captchaimage"><a href="/register" id="refreshimg" title="Click to refresh image"><img src="/images/captcha/index.php?<?php echo time(); ?>" width="132" height="46" alt="Captcha image" /></a></div>
                    <input class="input_field_12em" type="text" name="captcha" id="captcha" maxlength="6" required="required" />
                    <br />
                </fieldset>
			</div>
            <br />
			<input id="back" name="back" value="Back" type="reset" />
            <input id="next" name="next" value="Next" type="submit" />
		  </form>
        </div>