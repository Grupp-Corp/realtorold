<?php
class Search extends CCTemplate 
{
	// Variables
	protected $Stem;
	protected $highlight;
	protected $XSSCheck;
	protected $Pages;
	protected $GetDB;
	protected $Stemming;
	protected $lang;
	// Construct
	public function __construct($Stemming, $lang) {
		// DB Connection Settings
		parent::__construct();
		$this->GetDB = $this->db_conn;
		// Vars
		$this->Stemming = $Stemming;
		$this->lang = $lang;
		// Checking for constants
		// Getting stemming class
		if ($this->Stemming == 1) {
			$this->Stem = new Stemmer();
		}
	}
	// Simple Cleanse Search String
	public function CleanseSearchString($TheString) {
		// XSS Class
		$this->XSSCheck = new InputFilter(1, 1);
		if (isset($TheString)) {
			$TheString = $this->XSSCheck->process($TheString); // XSS Check
			//$TheString = preg_replace('/[^A-Za-z0-9_ +-]/i', "", $TheString); // remove chars we dont want 
			$TheString = preg_replace('/[^A-Za-z0-9_ \'"-@#]\w/i', "", $TheString); // remove chars we dont want 
			$TheString = preg_replace('/[\r\n\s]+/xms', ' ', trim($TheString)); // normalize spaces
		} else {
			$TheString = '';
		}
		return $TheString;
	}
	// Simple Cleanse Search String Proper
	public function CleanseSearchStringProper($TheString) {
		if (isset($TheString)) {
			$TheString = $this->XSSCheck->process($TheString); // XSS Check
			$TheString = preg_replace('/[^A-Za-z0-9_ \'"-%@#]\w/i', "", $TheString); // remove chars we dont want
			$TheString = preg_replace('/[\r\n\s]+/xms', ' ', trim($TheString)); // normalize spaces
		} else {
			$TheString = '';
		}
		return $TheString;
	}
	// Simple Cleanse Search Type
	public function CleanseSearchType($TheType) {
		if (isset($TheType)) {
			$TheType = $this->XSSCheck->process($TheType);
			$TheType = preg_replace('/[^0-9 ]/i', "", $TheType);
			if (preg_match('/"/', $TheType)) {
				$TheType = 0;
			}
		} else {
			$TheType = 1;
		}
		return $TheType;
	}
	// Simple Cleanse Search Return
	public function CleanseSearchButton($TheButton) {
		if (isset($TheButton)) {
			$TheButton = $this->XSSCheck->process($TheButton);
			$TheButton = preg_replace('/[^A-Za-z ]/i', "", $TheButton);
		} else {
			$TheButton = 'All Results';
		}
		return $TheButton;
	}
	// Stop words
	public function StopWords($val = '') {
		$StopWords ="a, able, about, above, abst, accordance, according, accordingly, across, act, actually, added, adj, affected, affecting, affects, after, afterwards, again, against, ah, all, almost, alone, along, already, also, although, always, am, among, amongst, an, and, announce, another, any, anybody, anyhow, anymore, anyone, anything, anyway, anyways, anywhere, apparently, approximately, are, aren, arent, arise, around, as, aside, ask, asking, at, auth, available, away, awfully, b, back, be, became, because, become, becomes, becoming, been, before, beforehand, begin, beginning, beginnings, begins, behind, being, believe, below, beside, besides, between, beyond, biol, both, brief, briefly, but, by, c, ca, came, can, cannot, can't, cause, causes, certain, certainly, co, com, come, comes, contain, containing, contains, could, couldnt, d, date, did, didn't, different, do, does, doesn't, doing, done, don't, down, downwards, due, during, e, each, ed, edu, effect, eg, eight, eighty, either, else, elsewhere, end, ending, enough, especially, et, et-al, etc, even, ever, every, everybody, everyone, everything, everywhere, ex, except, f, far, few, ff, fifth, first, five, fix, followed, following, follows, for, former, formerly, forth, found, four, from, further, furthermore, g, gave, get, gets, getting, give, given, gives, giving, go, goes, gone, got, gotten, h, had, happens, hardly, has, hasn't, have, haven't, having, he, hed, hence, her, here, hereafter, hereby, herein, heres, hereupon, hers, herself, hes, hi, hid, him, himself, his, hither, home, how, howbeit, however, hundred, i, id, ie, if, i'll, im, immediate, immediately, importance, important, in, inc, indeed, index, information, instead, into, invention, inward, is, isn't, it, itd, it'll, its, itself, i've, j, just, k, keep, keeps, kept, kg, km, know, known, knows, l, largely, last, lately, later, latter, latterly, least, less, lest, let, lets, like, liked, likely, line, little, 'll, look, looking, looks, ltd, m, made, mainly, make, makes, many, may, maybe, me, mean, means, meantime, meanwhile, merely, mg, might, million, miss, ml, more, moreover, most, mostly, mr, mrs, much, mug, must, my, myself, n, na, name, namely, nay, nd, near, nearly, necessarily, necessary, need, needs, neither, never, nevertheless, new, next, nine, ninety, no, nobody, non, none, nonetheless, noone, nor, normally, nos, not, noted, nothing, now, nowhere, o, obtain, obtained, obviously, of, off, often, oh, ok, okay, old, omitted, on, once, one, ones, only, onto, or, ord, other, others, otherwise, ought, our, ours, ourselves, out, outside, over, overall, owing, own, p, page, pages, part, particular, particularly, past, per, perhaps, placed, please, plus, poorly, possible, possibly, potentially, pp, predominantly, present, previously, primarily, probably, promptly, proud, provides, put, q, que, quickly, quite, qv, r, ran, rather, rd, re, readily, really, recent, recently, ref, refs, regarding, regardless, regards, related, relatively, research, respectively, resulted, resulting, results, right, run, s, said, same, saw, say, saying, says, sec, section, see, seeing, seem, seemed, seeming, seems, seen, self, selves, sent, seven, several, shall, she, shed, she'll, shes, should, shouldn't, show, showed, shown, showns, shows, significant, significantly, similar, similarly, since, six, slightly, so, some, somebody, somehow, someone, somethan, something, sometime, sometimes, somewhat, somewhere, soon, sorry, specifically, specified, specify, specifying, still, stop, strongly, sub, substantially, successfully, such, sufficiently, suggest, sup, sure, take, taken, taking, tell, tends, th, than, thank, thanks, thanx, that, that'll, thats, that've, the, their, theirs, them, themselves, then, thence, there, thereafter, thereby, thered, therefore, therein, there'll, thereof, therere, theres, thereto, thereupon, there've, these, they, theyd, they'll, theyre, they've, think, this, those, thou, though, thoughh, thousand, throug, through, throughout, thru, thus, til, tip, to, together, too, took, toward, towards, tried, tries, truly, try, trying, ts, twice, two, u, un, under, unfortunately, unless, unlike, unlikely, until, unto, up, upon, ups, us, use, used, useful, usefully, usefulness, uses, using, usually, v, value, various, 've, very, via, viz, vol, vols, vs, w, want, wants, was, wasn't, way, we, wed, welcome, we'll, went, were, weren't, we've, what, whatever, what'll, whats, when, whence, whenever, where, whereafter, whereas, whereby, wherein, wheres, whereupon, wherever, whether, which, while, whim, whither, who, whod, whoever, whole, who'll, whom, whomever, whos, whose, why, widely, willing, wish, with, within, without, won't, words, world, would, wouldn't, www, x, y, yes, yet, you, youd, you'll, your, youre, yours, yourself, yourselves, you've, z, zero";
		$StopWords = explode(', ', $StopWords);
		if ((isset($val)) && ($val > '')) {
			$found = 0;
			foreach($StopWords as $word) {
				if ($word == $val) {
					$found = 1;
					break;
				}
			}
			if ($found == 1) {
				return true;
			} else {
				return false;
			}
		} else {
			return $StopWords;
		}
	}
	// Public Short Description
	public function ShortDesc($text, $search, $textLimit = 250){
		$dots = '';
		// Check Search
		if(!empty($search)) {
			$pos = stripos($text, $search);
			// Ensure a resturn
			if($pos !== false) {
				$pos = max($pos - 100, 0);
				// Check if the position is greater then zero
				if ($pos > 0) {
					$dots = '...';
				}
				// Build text string with pre-dots
				$text = $dots . substr($text, $pos);
			}
		}
		// Check if we need dots...
		if (strlen($text) > $textLimit) {
			$dots = '...';
		}
		// Build final string
		$final = substr($text, 0, $textLimit) . $dots;
		return $final;
	}
	// Search Porter Stemming
	protected function SearchStemming($word, $stemming = 1) {
		// Check for stemming
		if ($stemming == 1) { // stemming on
			// stemming word
			$stemmed_word = $this->Stem->Stem($word);
			if ((isset($stemmed_word)) && ($stemmed_word > '')) { // Check if stemmed word exists
				return $stemmed_word;
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
	// Entered word type - query build
	protected function WordTypeSearch($word, $SearchType = 2) {
		// Vars
		$LoopBuild = '';
		$Type = 0;
		$wordArray = array();
		// Check if string exists and is larger the nothing
		if ((isset($word)) && ($word > '')) {
			// Checking Word Entry Type
			if (preg_match('/^\+/', $word)) { // word exclusive
				$word = str_replace('+', '', $word); // Replace plus sign
				$wordArray[] = $word;
				$Type = 1;
				// No Stemming for "all" search types
				if ($SearchType != 1) {
					// Porter stemmed entry check
					$StemmedWord = $this->SearchStemming($word); // Get porter stemmed word
					if (($StemmedWord > '') && ($StemmedWord != $word)) { // Does the word exist and is it different then original?
						$wordArray[] = $StemmedWord;
					}
				}
			} elseif (preg_match('/^-/', $word)) { // word exclusion
				$word = str_replace('+', '', $word); // Replace plus sign
				$wordArray[] = $word;
				$Type = 2;
				// No Stemming for "all" search types
				if ($SearchType != 1) {
					// Porter stemmed entry check
					$StemmedWord = $this->SearchStemming($word); // Get porter stemmed word
					if (($StemmedWord > '') && ($StemmedWord != $word)) { // Does the word exist and is it different then original?
						$wordArray[] = $StemmedWord;
					}
				}
			} else { // word inclusion
				$Type = 3;
				$wordArray[] = $word;
				// No Stemming for "all" search types
				if ($SearchType != 1) {
					// Porter stemmed entry check
					$StemmedWord = $this->SearchStemming($word); // Get porter stemmed word
					if (($StemmedWord > '') && ($StemmedWord != $word)) { // Does the word exist and is it different then original?
						$wordArray[] = $StemmedWord;
					}
				}
			}
		}
		return array('WordArray' => $wordArray, 'Type' => $Type);
	}
	// Handling Word with +
	protected function PlusQueryBuild($PlusArray, $TermsGotten = 0) {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		// Check
		if ((isset($PlusArray)) && (count($PlusArray) > 0)) {
			// New Counter
			$k = 1;
			$ArrayCount = count($PlusArray);
			$open_bracket = 0;
			$LoopBuild = '';
			$JoinBuild = '';
			// Exclusive Loop
			foreach($PlusArray as $ExclusiveWord) {
				$ExclusiveWord = $this->CleanseSearchStringProper($ExclusiveWord);
				// Hex suffix
				$HEXWordFirstChar = substr(md5($ExclusiveWord), 0, 1);
				$JoinBuild .= ' LEFT JOIN ise_search_keyword' . $HEXWordFirstChar . '' . $langsql . ' ON ise_search_links' . $langsql . '.lid = ise_search_keyword' . $HEXWordFirstChar . '' . $langsql . '.lid ';
				// Check if this is first
				if ($TermsGotten == 0) {
					$LoopBuild .= ' (' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.keyword = "' . $this->GetDB->real_escape_string($ExclusiveWord) . '"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					$TermsGotten = 1;
					$open_bracket = 1;
				} else if (($TermsGotten == 0) && ($ArrayCount == $k)) {
					$LoopBuild .= ' ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.keyword = "' . $this->GetDB->real_escape_string($ExclusiveWord) . '"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					$TermsGotten = 1;
					if ($open_bracket == 1) {
						$LoopBuild .= ')';
						$open_bracket = 0;
					}
				} else {
					if ($k == 1) {
						$LoopBuild .= ' AND (' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.keyword = "' . $this->GetDB->real_escape_string($ExclusiveWord) . '"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$open_bracket = 1;
					} else if ($ArrayCount == $k) {
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.keyword = "' . $this->GetDB->real_escape_string($ExclusiveWord) . '"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						if ($open_bracket == 1) {
							$LoopBuild .= ')';
							$open_bracket = 0;
						}
					} else {
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '.keyword = "' . $this->GetDB->real_escape_string($ExclusiveWord) . '"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($ExclusiveWord) . '%"'; // query addition
					}
				}
				// Increment counter
				$k++;
			}
			// Closing Bracet
			if ($open_bracket == 1) {
				$LoopBuild .= ')';
			}
			// Resetting variables
			$TermsGotten = 1;
			return array('LoopBuild' => $LoopBuild, 'JoinBuild' => $JoinBuild, 'TermsGotten' => $TermsGotten);
		} else {
			return array('LoopBuild' => false, 'JoinBuild' => false, 'TermsGotten' => 0);
		}
	}
	// Handline Normal Words
	protected function NormalQueryBuild($NormalArray, $TermsGotten = 0) {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		$CountArray = count($NormalArray);
		// Check
		if ((isset($NormalArray)) && ($CountArray > 0)) {
			// New Counter
			$k = 1;
			$ArrayCount = count($NormalArray);
			$open_bracket = 0;
			$LoopBuild = '';
			$JoinBuild = '';
			$LoopBuild_Addendum = '';
			$LoopBuild_Order = '';
			// Normal Loop
			foreach($NormalArray as $NormalWord) {
				// Hex suffix
				$HEXWordFirstChar = substr(md5($NormalWord), 0, 1);
				//$JoinBuild .= ' LEFT JOIN ise_search_keyword' . $HEXWordFirstChar . '' . $langsql . ' ON ise_search_links' . $langsql . '.lid = ise_search_keyword' . $HEXWordFirstChar . '' . $langsql . '.lid ';
				// Check if this is first
				if ($TermsGotten == 0) {
					$LoopBuild .= ' ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					$TermsGotten = 1;
				} else {
					if ($k == 1) {
						$LoopBuild .= ' AND (' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
						$open_bracket = 1;
					} else if ($ArrayCount == $k) {
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
						if ($open_bracket == 1) {
							$LoopBuild .= ')';
							$open_bracket = 0;
						}
					} else {
						$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					}
				}
				// Checking links table
				$LoopBuild_Addendum .= ' OR (' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.author LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.subject LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.owner LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%")'; // query addition
				// Setting the loop order based on entries
				$LoopBuild_Order .= 
				'(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + 
				(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + 
				(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + 
				(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.subject LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + ';
				// Increment counter
				$k++;
			}
			$LoopBuild = $LoopBuild . $LoopBuild_Addendum;
			// Closing Bracet
			if ($open_bracket == 1) {
				$LoopBuild .= ')';
			}
			// Resetting variables
			$TermsGotten = 1;
			return array('LoopBuild' => $LoopBuild, 'JoinBuild' => $JoinBuild, 'LoopBuild_Order' => $LoopBuild_Order, 'TermsGotten' => $TermsGotten);
		} else {
			return array('LoopBuild' => false, 'JoinBuild' => false, 'LoopBuild_Order' => false, 'TermsGotten' => 0);
		}
	}
	// "Any" Search Type Query Build
	protected function AnyTypeQueryBuild($NormalArray, $TermsGotten = 0) {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		// Check
		if ((isset($NormalArray)) && (count($NormalArray) > 0)) {
			// New Counter
			$k = 1;
			$ArrayCount = count($NormalArray);
			$open_bracket = 0;
			$LoopBuild = '';
			$LoopBuild_Order = '';
			$LoopBuild_Addendum = '';
			// Normal Loop
			foreach($NormalArray as $NormalWord) {
				// Hex suffix
				$HEXWordFirstChar = substr(md5($NormalWord), 0, 1);
				//$JoinBuild .= ' LEFT JOIN ise_search_keyword' . $HEXWordFirstChar . '' . $langsql . ' ON ise_search_links' . $langsql . '.lid = ise_search_keyword' . $HEXWordFirstChar . '' . $langsql . '.lid ';
				// Check if this is first
				if ($TermsGotten == 0) {
					$LoopBuild .= ' (' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%")'; // query addition
					$TermsGotten = 1;
				} else {
					$LoopBuild .= ' AND (' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					$LoopBuild .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%")'; // query addition
				}
				// Checking links table
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.author LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.subject LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				$LoopBuild_Addendum .= ' OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.owner LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%")'; // query addition
				// Setting the loop order based on entries
				$LoopBuild_Order .= 
				'(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + 
				(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + 
				(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + 
				(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.subject LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + ';
				// Increment counter
				$k++;
			}
			// Closing Bracket
			return array('LoopBuild' => $LoopBuild, 'LoopBuild_Order' => $LoopBuild_Order, 'TermsGotten' => $TermsGotten);
		} else {
			return array('LoopBuild' => false, 'JoinBuild' => false, 'LoopBuild_Order' => false, 'TermsGotten' => 0);
		}
	}
	// Handle Word with -
	protected function MinusQueryBuild($MinusArray, $TermsGotten = 0) {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		// Check
		if ((isset($NormalArray)) && (count($NormalArray) > 0)) {
			// New Counter
			$k = 1;
			$ArrayCount = count($NormalArray);
			$open_bracket = 0;
			$LoopBuild = '';
			$LoopBuild_Order = '';
			$LoopBuild_Addendum = '';
			// Normal Loop
			foreach($NormalArray as $NormalWord) {
				// Check if this is first
				if ($TermsGotten == 0) {
					$LoopBuild .= ' ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title NOT LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
					$TermsGotten = 1;
				} else {
					$LoopBuild .= ' AND ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title NOT LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%"'; // query addition
				}
				// Setting the loop order based on entries
				$LoopBuild_Order .= 
				'(
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title NOT LIKE "%' . $this->GetDB->real_escape_string($NormalWord) . '%" 
					THEN 1
					ELSE 0
					END
				  ) + ';
				// Increment counter
				$k++;
			}
			// Closing Bracket
			return array('LoopBuild' => $LoopBuild, 'LoopBuild_Order' => $LoopBuild_Order, 'TermsGotten' => $TermsGotten);
		} else {
			return array('LoopBuild' => false, 'JoinBuild' => false, 'LoopBuild_Order' => false, 'TermsGotten' => 0);
		}
	}
	// Category Grab
	public function GetCategoryInfo($id) {
		if ((isset($id)) && (is_numeric($id))) {
			$queryString = 'SELECT * FROM ' . $this->config['table_prefix'] . 'search_categories WHERE id = ' . $id . '';
			$row = $this->GetDB->query($queryString);
			while ($rows = $row->fetch_array(MYSQLI_ASSOC)) {
				$repeat_rows[] = $rows;
			}
			return array('location' => $repeat_rows[0]['location'], 'title' => $repeat_rows[0]['title'], 'description' => $repeat_rows[0]['description']);
		}
	}
	// Get all Categories
	public function GetCategories() {
		// Global DB Connection
		global $db_conn;
		$queryString = 'SELECT * FROM ' . $this->config['table_prefix'] . 'search_categories ORDER BY title ASC';
		$rows = $this->GetDB->query($queryString);
		while ($row = $rows->fetch_array(MYSQLI_ASSOC)) {
			$repeat_rows[] = $row;
		}
		return $repeat_rows;
	}
	// Search Call
	public function SearchSite($GetArray, $Field, $SearchType = 1, $category = NULL, $Limit = array(0,25), $FirstResult = 0, $DidYouMean = 1, $stemming = 1) {
		// Check Language
		if ($this->lang > '') {
			$langsql = '_' . str_replace('-', '', $this->lang);
		} else {
			$langsql = '';
		}
		// Vars
		$Continue = 1;
		$SQLStem = '';
		// Checks
		if (!isset($GetArray)) {
			$Continue = 0;
		} elseif (!is_array($GetArray)) {
			$Continue = 0;
		}
		// Getting search field
		if (!isset($GetArray[$Field])) {
			$Continue = 0;
		} elseif (strlen($GetArray[$Field]) < 3) {
			$Continue = 0;
		}
		// Continue with script
		if ($Continue == 1) {
			// Getting type
			$type = $SearchType;
			// Checking for quotes
			if (preg_match('/"/', $GetArray[$Field])) {
				$type = 0;
			} elseif (preg_match('/&quot;/i', $GetArray[$Field])) {
				$type = 0;
			}
			// String cleanse
			$SQLSearchString = $this->CleanseSearchString($GetArray[$Field]);
			// Checking search type
			if (($type == 1) or ($type == 2)) { // Not a phrase
				// Vars
				$LoopBuild = '';
				$LoopBuild_Order = '';
				$JoinBuild = '';
				$TermsGotten = 0;
				$PlusArray = array();
				$MinusArray = array();
				$NormalArray = array();
				$i = 0; // Counter Start for loops
				// Spaced out terms (ha ha!)
				if ($TermsGotten == 0) {
					$Terms = explode(' ', $SQLSearchString);
					// Check terms
					if ((isset($Terms)) && (isset($Terms[1]))) {
						// Loop through terms
						foreach($Terms as $EWord) {
							// Check word type
							$WordReturn = $this->WordTypeSearch($EWord, $type); // Concatenate loop
							$Words = $WordReturn['WordArray'];
							$Type = $WordReturn['Type'];
							if ($Type > 0) {
								// Loop through returned words
								foreach($Words as $OneWord) {
									// Check Search Type and Word Type (allow only normal words)
									if (($type == 1) && ($Type == 3)) {
										$NormalArray[] = $OneWord;
									} else { // "Any" Search Type
										// Check type returned
										if ($Type == 1) { // Exclusive Words
											$PlusArray[] = $OneWord;
										} else if ($Type == 2) { // Exclude Words
											$MinusArray[] = $OneWord;
										} else if ($Type == 3) { // Normal Words
											$NormalArray[] = $OneWord;
										}
									}
								}
							}
							// Increment counter
							$i++;
							// Stopping at 15 terms
							if ($i == 14) { 
								break; // stop
							}
						}
						// Checking Search Type
						if ($type == 1) { // "Any" Words Search Type
							// Check Normal Array for "All" Search Type
							$NormalArray = $this->AnyTypeQueryBuild($NormalArray, $TermsGotten);
							if ($NormalArray['TermsGotten'] > 0) {
								if ($NormalArray['LoopBuild'] !== false) {
									$LoopBuild .= $NormalArray['LoopBuild'];
									$LoopBuild_Order .= $NormalArray['LoopBuild_Order'];
									//$JoinBuild .= $NormalArray['JoinBuild'];
									$TermsGotten = 1;
								}
							}
						} else { // "All" Words Search Type
							// Check Exclude Array
							$MinusArray = $this->MinusQueryBuild($MinusArray, $TermsGotten);
							if ($MinusArray['TermsGotten'] > 0) {
								if ($MinusArray['LoopBuild'] !== false) {
									$LoopBuild .= $MinusArray['LoopBuild'];
									$JoinBuild .= $MinusArray['JoinBuild'];
									$TermsGotten = 1;
								}
							}
							// Check Exclusive Array
							$PlusArray = $this->PlusQueryBuild($PlusArray, 0);
							if ($PlusArray['TermsGotten'] > 0) {
								if ($PlusArray['LoopBuild'] !== false) {
									$LoopBuild .= $PlusArray['LoopBuild'];
									$JoinBuild .= $PlusArray['JoinBuild'];
									$TermsGotten = 1;
									//echo $LoopBuild;
								}
							}
							// Check Normal Array
							$NormalArray = $this->NormalQueryBuild($NormalArray, $TermsGotten);
							if ($NormalArray['TermsGotten'] > 0) {
								if ($NormalArray['LoopBuild'] !== false) {
									$LoopBuild .= $NormalArray['LoopBuild'];
									$LoopBuild_Order .= $NormalArray['LoopBuild_Order'];
									$JoinBuild .= $NormalArray['JoinBuild'];
									$TermsGotten = 1;
								}
							}
						}
					}
				}
				// Terms concatenated with + signs (if spaces did not exist above)
				if ($TermsGotten == 0) {
					$PlusTerms = explode('+', $SQLSearchString);
					// Do we have more then 1 term?
					if ((isset($PlusTerms)) && (isset($PlusTerms[1]))) {
						// Looping through terms found
						foreach($PlusTerms as $PlusEWord) {
							// Check word type
							$WordReturn = $this->WordTypeSearch($PlusEWord); // Concatenate loop
							$Words = $WordReturn['WordArray'];
							$Type = $WordReturn['Type'];
							// Loop through returned words
							foreach($Words as $OneWord) {
								// Check type returned
								if ($Type == 1) { // Exclusive Words
									$PlusArray[] = $OneWord;
								} else if ($Type == 2) { // Exclude Words
									$MinusArray[] = $OneWord;
								} else if ($Type == 3) { // Normal Words
									$NormalArray[] = $OneWord;
								}
							}
							// Increment counter
							$i++;
							// Stopping at 15 terms
							if ($i == 14) { // Stopping at 15 terms
								break; // stop
							}
						}
						// Check Exclude Array
						$MinusArray = $this->NormalQueryBuild($MinusArray, $TermsGotten);
						if ($MinusArray['TermsGotten'] > 0) {
							if ($MinusArray['LoopBuild'] !== false) {
								$LoopBuild .= $MinusArray['LoopBuild'];
								$JoinBuild .= $MinusArray['JoinBuild'];
								$TermsGotten = 1;
							}
						}
						// Check Exclusive Array
						$PlusArray = $this->PlusQueryBuild($PlusArray, 0);
						if ($PlusArray['TermsGotten'] > 0) {
							if ($PlusArray['LoopBuild'] !== false) {
								$LoopBuild .= $PlusArray['LoopBuild'];
								$JoinBuild .= $PlusArray['JoinBuild'];
								$TermsGotten = 1;
							}
						}
						// Check Normal Array
						$NormalArray = $this->NormalQueryBuild($NormalArray, $TermsGotten);
						if ($NormalArray['TermsGotten'] > 0) {
							if ($NormalArray['LoopBuild'] !== false) {
								$LoopBuild .= $NormalArray['LoopBuild'];
								$JoinBuild .= $NormalArray['JoinBuild'];
								$LoopBuild_Order .= $NormalArray['LoopBuild_Order'];
								$TermsGotten = 1;
							}
						}
					}
				}
				// Default
				if ($TermsGotten == 0) {
					// Check word type
					$WordReturn = $this->WordTypeSearch($SQLSearchString); // Concatenate loop
					$Words = $WordReturn['WordArray'];
					$Type = $WordReturn['Type'];
					// Loop through returned words
					foreach($Words as $OneWord) {
						// Check type returned
						if ($Type == 1) { // Exclusive Words
							$PlusArray[] = $OneWord;
						} else if ($Type == 2) { // Exclude Words
							$MinusArray[] = $OneWord;
						} else if ($Type == 3) { // Normal Words
							$NormalArray[] = $OneWord;
						}
					}
					// Check Exclusive Array
					$PlusArray = $this->PlusQueryBuild($PlusArray, 0);
					if ($PlusArray['TermsGotten'] > 0) {
						if ($PlusArray['LoopBuild'] !== false) {
							$LoopBuild .= $PlusArray['LoopBuild'];
							$JoinBuild .= $PlusArray['JoinBuild'];
							$TermsGotten = 1;
						}
					}
					// Check Normal Array
					$NormalArray = $this->NormalQueryBuild($NormalArray, $TermsGotten);
					if ($NormalArray['TermsGotten'] > 0) {
						if ($NormalArray['LoopBuild'] !== false) {
							$LoopBuild .= $NormalArray['LoopBuild'];
							$JoinBuild .= $NormalArray['JoinBuild'];
							$LoopBuild_Order .= $NormalArray['LoopBuild_Order'];
							$TermsGotten = 1;
						}
					}
					// Check Exclude Array
					$MinusArray = $this->NormalQueryBuild($MinusArray, $TermsGotten);
					if ($MinusArray['TermsGotten'] > 0) {
						if ($MinusArray['LoopBuild'] !== false) {
							$LoopBuild .= $MinusArray['LoopBuild'];
							$JoinBuild .= $MinusArray['JoinBuild'];
							$TermsGotten = 1;
						}
					}
				}
				// Hex suffix
				$HEXWordFirstChar = substr(md5($SQLSearchString), 0, 1);
				// Build Query String
				$queryString = 'SELECT *  
				FROM ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' ';
				//' . $JoinBuild . ' 
				$queryString .= 'WHERE ';
				// Check category
				if ((isset($category)) && ($category != NULL)) {
					$categoryLink = $this->GetCategoryInfo($category);
					$queryString .= ' ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.link LIKE "' . $categoryLink['location'] . '%" AND ';
				}
				// Check type
				if ($type == 2) {
					$queryString .= '(' . str_replace(' AND ', ' OR ', $LoopBuild) . ') ';
				} else {
					$queryString .= '(' . $LoopBuild . ') ';
				}
				// Group by/Order by
				$queryString .= 'GROUP BY ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.lid 
				ORDER BY (
				  ';
				// Fix for trailing plus sign
				$queryString .= preg_replace('/\+$/', '', trim($LoopBuild_Order));
				// Limit/Order
				$queryString .= ') DESC  LIMIT ' . $Limit[0] . ', ' . $Limit[1] . '';
				//echo nl2br($queryString);
			} else { // Phrase search
				// Cleanse String
				$SQLSearchString = $this->GetDB->real_escape_string($SQLSearchString);
				// Phrase Query
				$queryString = 'SELECT * 
				FROM ' . $this->config['table_prefix'] . 'search_links' . $langsql . ' 
				WHERE ';
				if ((isset($category)) && ($category != NULL)) {
					$categoryLink = $this->GetCategoryInfo($category);
					$queryString .= ' ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.link LIKE "%' . $categoryLink['location'] . '%" AND ';
				}
				$queryString .= '(' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "% ' . $SQLSearchString . ' %" 
				OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "% ' . $SQLSearchString . ' %" 
				OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "% ' . $SQLSearchString . ' %" 
				OR ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.subject LIKE "% ' . $SQLSearchString . ' %") 
				ORDER BY (
				  (
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.title LIKE "% ' . $SQLSearchString . ' %" 
					THEN 1
					ELSE 0
					END
				  ) + (
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.description LIKE "% ' . $SQLSearchString . ' %" 
					THEN 1
					ELSE 0
					END
				  ) + (
					CASE WHEN ' . $this->config['table_prefix'] . 'search_links' . $langsql . '.body LIKE "% ' . $SQLSearchString . ' %" 
					THEN 1
					ELSE 0
					END
				  )
				) DESC 
				LIMIT ' . $Limit[0] . ', ' . $Limit[1] . '
				';
				//echo nl2br($queryString);
			}
			// Check what the user wants returned - Queries from strings above
			if ($FirstResult == 1) { // getting first result
				// Finding our search string match
				$rowSet = $this->GetDB->query($queryString);
				while ($rows = $rowSet->fetch_array(MYSQLI_ASSOC)) {
					$row[] = $rows;
				}
				$TotalRows = $this->GetDB->affected_rows;
				// Row check
				if ($TotalRows > 0) { // Redirect
					header('Location: ' . $row[0]['link'] . '');
				} else { // Get results
					// Finding our search string match
					$this->GetDB->query($queryString);
					$TotalRows = $this->GetDB->affected_rows;
				}
			} else { // Getting limited results
				// Finding our search string match
				$this->GetDB->query($queryString);
				$TotalRows = $this->GetDB->affected_rows;
			}
			// We have a search string to show - Query rows returned
			if ($TotalRows > 0) {
				$repeat_rows = $this->GetDB->query($queryString);
				while ($rows = $repeat_rows->fetch_array(MYSQLI_ASSOC)) {
					$repeat[] = $rows;
				}
				// Check type
				if (($type == 1) or ($type == 2)) { // Phrase
					// Loop through query array
					foreach ($repeat as $row) {
						$ResultArray['lid'][] = $row['lid'];
						$ResultArray['author'][] = $row['author'];
						$ResultArray['date_modified'][] = $row['date_modified'];
						$ResultArray['subject'][] = $row['subject'];
						$ResultArray['owner'][] = $row['owner'];
						$ResultArray['review_date'][] = $row['review_date'];
						$ResultArray['date_issued'][] = $row['date_issued'];
						$ResultArray['language'][] = $row['language'];
						$ResultArray['body'][] = $row['body'];
						$ResultArray['link'][] = $row['link'];
						$ResultArray['title'][] = $row['title'];
						$ResultArray['description'][] = $row['description'];
						$ResultArray['pagesize'][] = $row['pagesize'];
						$ResultArray['query'][] = $queryString;
					}
				} else { // Default Search
					// Loop through query array
					foreach ($repeat as $row) {
						$ResultArray['lid'][] = $row['lid'];
						$ResultArray['author'][] = $row['author'];
						$ResultArray['date_modified'][] = $row['date_modified'];
						$ResultArray['subject'][] = $row['subject'];
						$ResultArray['owner'][] = $row['owner'];
						$ResultArray['review_date'][] = $row['review_date'];
						$ResultArray['date_issued'][] = $row['date_issued'];
						$ResultArray['language'][] = $row['language'];
						$ResultArray['body'][] = $row['body'];
						$ResultArray['link'][] = $row['link'];
						$ResultArray['title'][] = $row['title'];
						$ResultArray['description'][] = $row['description'];
						$ResultArray['pagesize'][] = $row['pagesize'];
						$ResultArray['query'][] = $queryString;
					}
				}
				if (isset($ResultArray['lid'])) {
					// Results Arrays
					$ResultArray['lid'] = array_values($ResultArray['lid']);
					//$ResultArray['body'] = array_unique($ResultArray['body']); // remove duplicates
					$ResultArray['body'] = array_values($ResultArray['body']);
					//$ResultArray['link'] = array_unique($ResultArray['link']); // remove duplicates
					$ResultArray['link'] = array_values($ResultArray['link']);
					//$ResultArray['title'] = array_unique($ResultArray['title']); // remove duplicates
					$ResultArray['title'] = array_values($ResultArray['title']);
					//$ResultArray['description'] = array_unique($ResultArray['description']); // remove duplicates
					$ResultArray['description'] = array_values($ResultArray['description']);
					//$ResultArray['query'] = array_unique($ResultArray['query']); // remove duplicates
					$ResultArray['query'] = array_values($ResultArray['query']);
					//$ResultArray['author'] = array_unique($ResultArray['author']); // remove duplicates
					$ResultArray['author'] = array_values($ResultArray['author']);
					//$ResultArray['date_modified'] = array_unique($ResultArray['date_modified']); // remove duplicates
					$ResultArray['date_modified'] = array_values($ResultArray['date_modified']);
					//$ResultArray['subject'] = array_unique($ResultArray['subject']); // remove duplicates
					$ResultArray['subject'] = array_values($ResultArray['subject']);
					//$ResultArray['owner'] = array_unique($ResultArray['owner']); // remove duplicates
					$ResultArray['owner'] = array_values($ResultArray['owner']);
					//$ResultArray['review_date'] = array_unique($ResultArray['review_date']); // remove duplicates
					$ResultArray['review_date'] = array_values($ResultArray['review_date']);
					//$ResultArray['date_issued'] = array_unique($ResultArray['date_issued']); // remove duplicates
					$ResultArray['date_issued'] = array_values($ResultArray['date_issued']);
					//$ResultArray['pagesize'] = array_unique($ResultArray['pagesize']); // remove duplicates
					$ResultArray['pagesize'] = array_values($ResultArray['pagesize']);
					//$ResultArray['language'] = array_unique($ResultArray['language']); // remove duplicates
					$ResultArray['language'] = array_values($ResultArray['language']);
					$i = 0;
					// Loop through title array returning results
					foreach ($ResultArray['title'] as $show) {
						$TrueResult[] = array(
											  'id' => $ResultArray['lid'][$i], 
											  'author' => $ResultArray['author'][$i], 
											  'date_modified' => $ResultArray['date_modified'][$i], 
											  'subject' => $ResultArray['subject'][$i], 
											  'owner' => $ResultArray['owner'][$i], 
											  'review_date' => $ResultArray['review_date'][$i], 
											  'date_issued' => $ResultArray['date_issued'][$i], 
											  'language' => $ResultArray['language'][$i], 
											  'title' => $ResultArray['title'][$i], 
											  'description' => $ResultArray['description'][$i], 
											  'pagesize' => $ResultArray['pagesize'][$i], 
											  'link' => $ResultArray['link'][$i], 
											  'body' => $ResultArray['body'][$i], 
											  'query' => $ResultArray['query'][$i]
											  );
						$i++;
					}
				}
				// Return
				return array('ResultArray' => $TrueResult, 'SearchType' => $type);
			} else { // No results (what did you mean?)
				// input misspelled word
				$input = $SQLSearchString;
				// Hex suffix
				$HEXWordFirstChar = substr(md5($input), 0, 1);
				$queryString = 'SELECT keyword FROM ' . $this->config['table_prefix'] . 'search_keyword' . $HEXWordFirstChar . '' . $langsql . '';
				$row = $this->GetDB->query($queryString);
				$TotalRows = $this->GetDB->affected_rows;
				if ($TotalRows > 0) {
					// We have keywords...
					$KeywordTableArray = array(
											   '' . $this->config['table_prefix'] . 'search_keyword0' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword1' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword2' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword3' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword4' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword5' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword6' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword7' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword8' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword9' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyword0' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyworda' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keywordb' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keywordc' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keywordd' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keyworde' . $langsql . '', 
											   '' . $this->config['table_prefix'] . 'search_keywordf' . $langsql . ''
											   );
					$words = array();
					foreach ($KeywordTableArray as $tableKw) {
						$queryString = 'SELECT keyword FROM ' . $tableKw . '';
						$rowSet = $this->GetDB->query($queryString);
						$TotalRows = $this->GetDB->affected_rows;
						if ($TotalRows > 0) {
							while ($row = $rowSet->fetch_array(MYSQLI_ASSOC)) {
								$repeat[] = $row;
							}
							// Getting keywords from database
							foreach ($repeat as $kw) {
								$words[] = strtolower($kw['keyword']);
							}
						}
					}
					// no shortest distance found, yet
					$shortest = -1;
					// Words Array Start
					$WordArray = array();
					$html = '';
					// loop through words to find the closest
					foreach ($words as $word) {
						// calculate the distance between the input word, and the current word
						$lev = levenshtein($input, $word);
						// check for an exact match
						if ($lev == 0) {
							// closest word is this one (exact match)
							$closest = $word;
							$shortest = 0;
							// break out of the loop; we've found an exact match
							break;
						}
						// Building array and vars
						$WordArray[$lev] = trim($word);
						$closest  = trim($word);
						$shortest = $lev;
					}
					// sorting array by keys
					ksort($WordArray);
					// Building Content
					if ($shortest == 0) {
						$ValFirst = $closest;
					} else {
						// Counter
						$i = 1;
						// Looping through our word array stopping after 2 entries found
						foreach($WordArray as $key => $val) {
							if ($key < 5) {
								$val = preg_replace('/[^a-z0-9_]/i', "", $val);
								$StopWordArray = $this->StopWords();
								if (!in_array(trim($val), $StopWordArray)) {
									if ($i == 1) {
										$FirstVal = trim($val);
										$ValFirst = trim($val);
									} else {
										// Making sure we skip this if it's already happened
										if ($FirstVal != trim($val)) {
											$ValSecond = trim($val);
										}
										break;
									}
									$i++;
								}
							}
						}
					}
					// Check for first & second value
					if (!isset($ValFirst)) {
						$ValFirst = '';
					}
					if (!isset($ValSecond)) {
						$ValSecond = '';
					}
					if (!isset($shortest)) {
						$shortest = '';
					}
					return array('ValFirst' => $ValFirst, 'ValSecond' => $ValSecond, 'shortest' => $shortest);
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
	// Search Return
	public function SearchReturn($SearchPull = array(), $limit = 5, $page = 1, $SearchString = '', $SearchType = 1, $SearchNow = 'All Results', $category = NULL, $ajax = 0) {
		// Pagination Classe
		$this->Pages = new Pagination(1, 1, 1);
		// Vars
		$html = '';
		// Check if search string exists....
		if (isset($SearchPull)) {
			// Check/Set Search type
			if ((isset($SearchPull['SearchType'])) && ($SearchPull['SearchType'] > '')) {
				$SearchType = $SearchPull['SearchType']; // 0 = phrase, 1 = all, 2 = any
			}
			// Check is we have rows from method
			if ((isset($SearchPull['ResultArray'])) && (is_array($SearchPull['ResultArray']))) {
				// Fixing Query String and removing the limit
				$topLimit = $page * $limit;
				$newQuery = str_replace('LIMIT ' . $topLimit . ', ' . $limit, '', $SearchPull['ResultArray'][0]['query']);
				// Pagination
				$Pagination = $this->Pages->PagesBuilder($newQuery, $limit, $page, $ajax);
				// Check if pages exist
				if ($Pagination > '') {
					$html .= '<div class="alignRight">' . PHP_EOL;
					$html .= $Pagination . PHP_EOL;
					$html .= '</div>' . PHP_EOL;
				}
				// Content for results...
				$html .= '<div class="alignLeft">' . PHP_EOL;
				// looping through results
				$i = 0;
				// Proper Cleanse removing excess chars and porter stemming addition
				//$SearchString = $this->Stem->Stem($SearchString);
				$SearchString = $this->CleanseSearchStringProper($SearchString);
				// Loop
				foreach ($SearchPull['ResultArray'] as $show) {
					// Checking Body
					if ((isset($show['body'])) && ($show['body'] > '')) {
						$Regx = '/\(([A-Z]{2,})\)/';
						// Check Search Type
						if (($SearchType == 1) or ($SearchType == 2)) {
							// Body Parse
							$ShortBody = $this->ShortDesc(nl2br($show['body']), $SearchString, 300);
							$ShortBody = str_replace(PHP_EOL, ' ', $ShortBody);
							if (preg_match($Regx, $ShortBody)) {
								$NewShortBody = preg_replace($Regx, '(<abbr>$1</abbr>)', $ShortBody);
							} else {
								$NewShortBody = $ShortBody;
							}
							$Highlighter = $this->HighlightSearchTerm($NewShortBody, $SearchString);
							if (preg_match($Regx, $show['title'])) {
								$NewTitle = preg_replace($Regx, '(<abbr>$1</abbr>)', $show['title']);
							} else {
								$NewTitle = $show['title'];
							}
							$HighlightTitle = $this->HighlightSearchTerm($NewTitle, $SearchString);
						} else {
							// Body Parse
							$ShortBody = $this->ShortDesc(nl2br($show['body']), $SearchString, 500);
							if (preg_match($Regx, $ShortBody)) {
								$NewShortBody = preg_replace($Regx, '(<abbr>$1</abbr>)', $ShortBody);
							} else {
								$NewShortBody = $ShortBody;
							}
							$Highlighter = $this->HighlightSearchPhrase($NewShortBody, $SearchString);
							if (preg_match($Regx, $show['title'])) {
								$NewTitle = preg_replace($Regx, '(<abbr>$1</abbr>)', $show['title']);
							} else {
								$NewTitle = $show['title'];
							}
							$HighlightTitle = $this->HighlightSearchPhrase($NewTitle, $SearchString);
						}
						// Highlighter failed
						if ($Highlighter === false) {
							$Highlighter['text'] = '';
						}
					} else {
						$Highlighter = '';
						$relevance = 0;
					}
					// Build Content from array
					$html .= '<a href="' . strip_tags($show['link']) . '" title="' . strip_tags($show['title']) . '"><span class="underline largetext">' . $HighlightTitle['text'] . '</span></a>' . PHP_EOL;
					$html .= '<br />' . PHP_EOL;
					$html .= '<label class="SearchLink searchlink" title="' . strip_tags($show['title']) . '"><strong>Link:&nbsp;</strong>' . $show['link'] . '</label>' . PHP_EOL;
					if (trim($show['author']) > '') {
						if (($SearchType == 1) or ($SearchType == 2)) {
							$HighlightAuthor = $this->HighlightSearchTerm($show['author'], $SearchString);
						} else {
							$HighlightAuthor = $this->HighlightSearchPhrase($show['author'], $SearchString);
						}
						$html .= '<span class="smalltext"><strong>Author:&nbsp;</strong>' . $HighlightAuthor['text'] . '</span>' . PHP_EOL;
					}
					if (trim($show['owner']) > '') {
						if (($SearchType == 1) or ($SearchType == 2)) {
							$HighlightOwner = $this->HighlightSearchTerm($show['owner'], $SearchString);
						} else {
							$HighlightOwner = $this->HighlightSearchPhrase($show['owner'], $SearchString);
						}
						$html .= '<span class="smalltext"><strong>Owner:&nbsp;</strong>' . $HighlightOwner['text'] . '</span>' . PHP_EOL;
					}
					if (trim($show['subject']) > '') {
						if (preg_match($Regx, $show['subject'])) {
							$NewSubject = preg_replace($Regx, '(<abbr>$1</abbr>)', $show['subject']);
						} else {
							$NewSubject = $show['subject'];
						}
						if (($SearchType == 1) or ($SearchType == 2)) {
							$HighlightSubject = $this->HighlightSearchTerm($NewSubject, $SearchString);
						} else {
							$HighlightSubject = $this->HighlightSearchPhrase($NewSubject, $SearchString);
						}
						$html .= '<span class="smalltext"><strong>Subject:&nbsp;</strong>' . $HighlightSubject['text'] . '</span>' . PHP_EOL;
					}
					if (trim($show['description']) > '') {
						if (preg_match($Regx, $show['description'])) {
							$NewDesc = preg_replace($Regx, '(<abbr>$1</abbr>)', $show['description']);
						} else {
							$NewDesc = $show['description'];
						}
						if (($SearchType == 1) or ($SearchType == 2)) {
							$HighlightDescription = $this->HighlightSearchTerm($NewDesc, $SearchString);
						} else {
							$HighlightDescription = $this->HighlightSearchPhrase($NewDesc, $SearchString);
						}
						$html .= '<span class="smalltext"><strong>Description:&nbsp;</strong>' . $HighlightDescription['text'] . '</span>' . PHP_EOL;
					}
					//$html .= '<label title="Word Count"><span class="smalltext"><strong>Word Count</strong>:' . $Highlighter['matches'] . '</span></label><br />' . PHP_EOL;
					$html .= '<span class="smalltext"><strong>Body:&nbsp;</strong>' . $Highlighter['text'] . '</span>' . PHP_EOL;
					if (trim($show['pagesize']) > '') {
						$html .= '<span class="smalltext"><strong>Page Size:&nbsp;</strong>' . $show['pagesize'] . '</span>' . PHP_EOL;
					}
					$html .= '<br />' . PHP_EOL;
					$i++;
				}
				$html .= '</div>';
				// Check if pages exist
				if ($Pagination > '') {
					$html .= '<div class="alignRight">' . PHP_EOL;
					$html .= $Pagination . PHP_EOL;
					$html .= '</div>' . PHP_EOL;
				}
			} else if ((isset($SearchPull['shortest'])) && ($SearchPull['shortest'] == 0)) { // What did you mean? (No results)
				// Build content from suggest array
				$html .= '<p class="alignCenter"><strong>Exact match found:</strong> <a href="?SearchString=' . $SearchPull['ValFirst'] . '&amp;SearchType=' . $SearchType . '&amp;SearchNow=' . $SearchNow . '"><span class="underline">' . $SearchPull['ValFirst'] . '</span></a></p>' . PHP_EOL;
			} else if ((isset($SearchPull['ValFirst'])) && ($SearchPull['ValFirst'] > '')) { // Partial Match (what did you mean?)
				// Content
				$html .= '<p class="alignCenter"><strong>Did you mean:</strong> <a href="?SearchString=' . $SearchPull['ValFirst'] . '&amp;Category=' . $category . '&amp;SearchType=' . $SearchType . '&amp;SearchNow=' . $SearchNow . '"><span class="underline">' . $SearchPull['ValFirst'] . '</span></a>' . PHP_EOL;
				// Check for second suggestion
				if ((isset($SearchPull['ValSecond'])) && ($SearchPull['ValSecond'] > '')) {
					if ($SearchPull['ValFirst'] != $SearchPull['ValSecond']) {
						$html .= ' or <a href="?SearchString=' . $SearchPull['ValSecond'] . '&amp;Category=' . $category . '&amp;SearchType=' . $SearchType . '&amp;SearchNow=' . $SearchNow . '"><span class="underline">' . $SearchPull['ValSecond'] . '</span></a>' . PHP_EOL;
					}
				}
				// Terminate our html paragraph
				$html .= '?</p>' . PHP_EOL;
			} else {
				$html .= '<p class="alignCenter"><strong>No results found.</strong></p>' . PHP_EOL;	
			}
		}
		// return
		return $html;
	}
	// Term
	public function HighlightSearchTerm($text, $words) {
		// variables
		$words = str_replace("+", ' ', $words);
		$match = 1;
		$textArray = explode(' ', $text);
		// Loop through spaced terms
		foreach ($textArray as $key => $word) {
			// Set/replace term characters
			$word = preg_replace('/[^a-z0-9_]/i', "", $word);
			$TextWords[] = $word;
		} 
		// Count words from above loop
		$countWords = array_count_values($TextWords);
		// Sorting words
		array_multisort($countWords, SORT_DESC);
		// Loop through words and find matches
		foreach ($countWords as $key => $value) {
			if (strtolower($key) == strtolower($words)) {
				$match = $value;
			}
		}
		// Split words by spae
		$split_words = explode(" ", $words);
		// Loop through split words make sure their not stop words and set text to output
		foreach ($split_words as $word) {
			if ($this->StopWords($word) === false) {
				$text = preg_replace("|($word)|Ui", '<strong>$1</strong>', $text);
			}
		}
		return array('text' => $text, 'matches' => $match);
	}
	// Phrase
	public function HighlightSearchPhrase($text, $words) {
		// variables
		$words = $words;
		//$words = str_replace("+", ' ', $words);
		$match = 1;
		$textArray = explode(' ', $text);
		// Loop through spaced terms
		foreach ($textArray as $key => $word) {
			// Set/replace term characters
			$word = preg_replace('/[^a-z0-9_]/i', "", $word);
			$TextWords[] = $word;
		} 
		// Count words from above loop
		$countWords = array_count_values($TextWords);
		// Sorting words
		array_multisort($countWords, SORT_DESC);
		// Loop through words and find matches
		foreach ($countWords as $key => $value) {
			if (strtolower($key) == strtolower($words)) {
				$match = $value;
			}
		}
		// Split words by spae
		$split_words = explode(" ", $words);
		// Loop through split words make sure their not stop words and set text to output
		foreach ($split_words as $word) {
			if ($this->StopWords($word) === false) {
				$text = preg_replace("|( $words )|Ui", '<strong>$1</strong>', $text);
			}
		}
		return array('text' => $text, 'matches' => $match);
	}
}
?>