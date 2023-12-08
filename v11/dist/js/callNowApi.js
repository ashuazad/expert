var records = new Object();
var recordId = null;
var closeButtonHtml = ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
var api = 'getQuotations.php';
let jobStatus = false;
let isJobRunning = false;
let isFilterResult = false;
let currentFilterObject = 'leads';
let currentFilter;

import {getCommon, getDefaultConfig} from "./common";
import {renderGrid} from "./communicationApi";

let defaultConfig = getDefaultConfig();

// Set Delayed Job

// Set Delayed Job

// Get Delayed Job Status

// Get Delayed Job Status

/// Default Load ///
renderGrid('.apiList');
//hideDisplayFilterResult();
/// Default Load ///