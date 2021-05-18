<?php

namespace PHPMaker2021\inplaze;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class BlogEdit extends Blog
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'blog';

    // Page object name
    public $PageObjName = "BlogEdit";

    // Rendering View
    public $RenderingView = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (blog)
        if (!isset($GLOBALS["blog"]) || get_class($GLOBALS["blog"]) == PROJECT_NAMESPACE . "blog") {
            $GLOBALS["blog"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'blog');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("blog"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "BlogView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['Blog_ID'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->Blog_ID->Visible = false;
        }
    }

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        $lookup->toJson($this); // Use settings from current page
    }
    public $FormClassName = "ew-horizontal ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->Blog_ID->Visible = false;
        $this->Image->setVisibility();
        $this->Title->setVisibility();
        $this->Intro->setVisibility();
        $this->_Content->setVisibility();
        $this->Priority->Visible = false;
        $this->_New->setVisibility();
        $this->View->Visible = false;
        $this->Enable->Visible = false;
        $this->Images->setVisibility();
        $this->Created->Visible = false;
        $this->Modified->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("Blog_ID") ?? Key(0) ?? Route(2)) !== null) {
                $this->Blog_ID->setQueryStringValue($keyValue);
                $this->Blog_ID->setOldValue($this->Blog_ID->QueryStringValue);
            } elseif (Post("Blog_ID") !== null) {
                $this->Blog_ID->setFormValue(Post("Blog_ID"));
                $this->Blog_ID->setOldValue($this->Blog_ID->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("Blog_ID") ?? Route("Blog_ID")) !== null) {
                    $this->Blog_ID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->Blog_ID->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                // Load current record
                $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$loaded) { // Load record based on key
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("BlogList"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "BlogList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Visible", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Rendering event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->Image->Upload->Index = $CurrentForm->Index;
        $this->Image->Upload->uploadFile();
        $this->Image->CurrentValue = $this->Image->Upload->FileName;
        $this->Images->Upload->Index = $CurrentForm->Index;
        $this->Images->Upload->uploadFile();
        $this->Images->CurrentValue = $this->Images->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'Title' first before field var 'x_Title'
        $val = $CurrentForm->hasValue("Title") ? $CurrentForm->getValue("Title") : $CurrentForm->getValue("x_Title");
        if (!$this->Title->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Title->Visible = false; // Disable update for API request
            } else {
                $this->Title->setFormValue($val);
            }
        }

        // Check field name 'Intro' first before field var 'x_Intro'
        $val = $CurrentForm->hasValue("Intro") ? $CurrentForm->getValue("Intro") : $CurrentForm->getValue("x_Intro");
        if (!$this->Intro->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Intro->Visible = false; // Disable update for API request
            } else {
                $this->Intro->setFormValue($val);
            }
        }

        // Check field name 'Content' first before field var 'x__Content'
        $val = $CurrentForm->hasValue("Content") ? $CurrentForm->getValue("Content") : $CurrentForm->getValue("x__Content");
        if (!$this->_Content->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Content->Visible = false; // Disable update for API request
            } else {
                $this->_Content->setFormValue($val);
            }
        }

        // Check field name 'New' first before field var 'x__New'
        $val = $CurrentForm->hasValue("New") ? $CurrentForm->getValue("New") : $CurrentForm->getValue("x__New");
        if (!$this->_New->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_New->Visible = false; // Disable update for API request
            } else {
                $this->_New->setFormValue($val);
            }
        }

        // Check field name 'Modified' first before field var 'x_Modified'
        $val = $CurrentForm->hasValue("Modified") ? $CurrentForm->getValue("Modified") : $CurrentForm->getValue("x_Modified");
        if (!$this->Modified->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Modified->Visible = false; // Disable update for API request
            } else {
                $this->Modified->setFormValue($val);
            }
            $this->Modified->CurrentValue = UnFormatDateTime($this->Modified->CurrentValue, 0);
        }

        // Check field name 'Blog_ID' first before field var 'x_Blog_ID'
        $val = $CurrentForm->hasValue("Blog_ID") ? $CurrentForm->getValue("Blog_ID") : $CurrentForm->getValue("x_Blog_ID");
        if (!$this->Blog_ID->IsDetailKey) {
            $this->Blog_ID->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->Blog_ID->CurrentValue = $this->Blog_ID->FormValue;
        $this->Title->CurrentValue = $this->Title->FormValue;
        $this->Intro->CurrentValue = $this->Intro->FormValue;
        $this->_Content->CurrentValue = $this->_Content->FormValue;
        $this->_New->CurrentValue = $this->_New->FormValue;
        $this->Modified->CurrentValue = $this->Modified->FormValue;
        $this->Modified->CurrentValue = UnFormatDateTime($this->Modified->CurrentValue, 0);
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->Blog_ID->setDbValue($row['Blog_ID']);
        $this->Image->Upload->DbValue = $row['Image'];
        $this->Image->setDbValue($this->Image->Upload->DbValue);
        $this->Title->setDbValue($row['Title']);
        $this->Intro->setDbValue($row['Intro']);
        $this->_Content->setDbValue($row['Content']);
        $this->Priority->setDbValue($row['Priority']);
        $this->_New->setDbValue($row['New']);
        $this->View->setDbValue($row['View']);
        $this->Enable->setDbValue($row['Enable']);
        $this->Images->Upload->DbValue = $row['Images'];
        $this->Images->setDbValue($this->Images->Upload->DbValue);
        $this->Created->setDbValue($row['Created']);
        $this->Modified->setDbValue($row['Modified']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Blog_ID'] = null;
        $row['Image'] = null;
        $row['Title'] = null;
        $row['Intro'] = null;
        $row['Content'] = null;
        $row['Priority'] = null;
        $row['New'] = null;
        $row['View'] = null;
        $row['Enable'] = null;
        $row['Images'] = null;
        $row['Created'] = null;
        $row['Modified'] = null;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // Blog_ID

        // Image

        // Title

        // Intro

        // Content

        // Priority

        // New

        // View

        // Enable

        // Images

        // Created

        // Modified
        if ($this->RowType == ROWTYPE_VIEW) {
            // Image
            if (!EmptyValue($this->Image->Upload->DbValue)) {
                $this->Image->ImageWidth = 0;
                $this->Image->ImageHeight = 100;
                $this->Image->ImageAlt = $this->Image->alt();
                $this->Image->ViewValue = $this->Image->Upload->DbValue;
            } else {
                $this->Image->ViewValue = "";
            }
            $this->Image->ViewCustomAttributes = "";

            // Title
            $this->Title->ViewValue = $this->Title->CurrentValue;
            $this->Title->ViewCustomAttributes = "";

            // Intro
            $this->Intro->ViewValue = $this->Intro->CurrentValue;
            $this->Intro->ViewCustomAttributes = "";

            // Content
            $this->_Content->ViewValue = $this->_Content->CurrentValue;
            $this->_Content->ViewCustomAttributes = "";

            // New
            if (ConvertToBool($this->_New->CurrentValue)) {
                $this->_New->ViewValue = $this->_New->tagCaption(2) != "" ? $this->_New->tagCaption(2) : "Yes";
            } else {
                $this->_New->ViewValue = $this->_New->tagCaption(1) != "" ? $this->_New->tagCaption(1) : "No";
            }
            $this->_New->ViewCustomAttributes = "";

            // Images
            if (!EmptyValue($this->Images->Upload->DbValue)) {
                $this->Images->ImageAlt = $this->Images->alt();
                $this->Images->ViewValue = $this->Images->Upload->DbValue;
            } else {
                $this->Images->ViewValue = "";
            }
            $this->Images->ViewCustomAttributes = "";

            // Created
            $this->Created->ViewValue = $this->Created->CurrentValue;
            $this->Created->ViewValue = FormatDateTime($this->Created->ViewValue, 0);
            $this->Created->ViewCustomAttributes = "";

            // Modified
            $this->Modified->ViewValue = $this->Modified->CurrentValue;
            $this->Modified->ViewValue = FormatDateTime($this->Modified->ViewValue, 0);
            $this->Modified->ViewCustomAttributes = "";

            // Image
            $this->Image->LinkCustomAttributes = "";
            if (!EmptyValue($this->Image->Upload->DbValue)) {
                $this->Image->HrefValue = GetFileUploadUrl($this->Image, $this->Image->htmlDecode($this->Image->Upload->DbValue)); // Add prefix/suffix
                $this->Image->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->Image->HrefValue = FullUrl($this->Image->HrefValue, "href");
                }
            } else {
                $this->Image->HrefValue = "";
            }
            $this->Image->ExportHrefValue = $this->Image->UploadPath . $this->Image->Upload->DbValue;
            $this->Image->TooltipValue = "";
            if ($this->Image->UseColorbox) {
                if (EmptyValue($this->Image->TooltipValue)) {
                    $this->Image->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->Image->LinkAttrs["data-rel"] = "blog_x_Image";
                $this->Image->LinkAttrs->appendClass("ew-lightbox");
            }

            // Title
            $this->Title->LinkCustomAttributes = "";
            $this->Title->HrefValue = "";
            $this->Title->TooltipValue = "";

            // Intro
            $this->Intro->LinkCustomAttributes = "";
            $this->Intro->HrefValue = "";
            $this->Intro->TooltipValue = "";

            // Content
            $this->_Content->LinkCustomAttributes = "";
            $this->_Content->HrefValue = "";
            $this->_Content->TooltipValue = "";

            // New
            $this->_New->LinkCustomAttributes = "";
            $this->_New->HrefValue = "";
            $this->_New->TooltipValue = "";

            // Images
            $this->Images->LinkCustomAttributes = "";
            if (!EmptyValue($this->Images->Upload->DbValue)) {
                $this->Images->HrefValue = "%u"; // Add prefix/suffix
                $this->Images->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->Images->HrefValue = FullUrl($this->Images->HrefValue, "href");
                }
            } else {
                $this->Images->HrefValue = "";
            }
            $this->Images->ExportHrefValue = $this->Images->UploadPath . $this->Images->Upload->DbValue;
            $this->Images->TooltipValue = "";
            if ($this->Images->UseColorbox) {
                if (EmptyValue($this->Images->TooltipValue)) {
                    $this->Images->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->Images->LinkAttrs["data-rel"] = "blog_x_Images";
                $this->Images->LinkAttrs->appendClass("ew-lightbox");
            }

            // Modified
            $this->Modified->LinkCustomAttributes = "";
            $this->Modified->HrefValue = "";
            $this->Modified->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // Image
            $this->Image->EditAttrs["class"] = "form-control";
            $this->Image->EditCustomAttributes = "";
            if (!EmptyValue($this->Image->Upload->DbValue)) {
                $this->Image->ImageWidth = 0;
                $this->Image->ImageHeight = 100;
                $this->Image->ImageAlt = $this->Image->alt();
                $this->Image->EditValue = $this->Image->Upload->DbValue;
            } else {
                $this->Image->EditValue = "";
            }
            if (!EmptyValue($this->Image->CurrentValue)) {
                $this->Image->Upload->FileName = $this->Image->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->Image);
            }

            // Title
            $this->Title->EditAttrs["class"] = "form-control";
            $this->Title->EditCustomAttributes = "";
            if (!$this->Title->Raw) {
                $this->Title->CurrentValue = HtmlDecode($this->Title->CurrentValue);
            }
            $this->Title->EditValue = HtmlEncode($this->Title->CurrentValue);
            $this->Title->PlaceHolder = RemoveHtml($this->Title->caption());

            // Intro
            $this->Intro->EditAttrs["class"] = "form-control";
            $this->Intro->EditCustomAttributes = "";
            $this->Intro->EditValue = HtmlEncode($this->Intro->CurrentValue);
            $this->Intro->PlaceHolder = RemoveHtml($this->Intro->caption());

            // Content
            $this->_Content->EditAttrs["class"] = "form-control";
            $this->_Content->EditCustomAttributes = "";
            $this->_Content->EditValue = HtmlEncode($this->_Content->CurrentValue);
            $this->_Content->PlaceHolder = RemoveHtml($this->_Content->caption());

            // New
            $this->_New->EditCustomAttributes = "";
            $this->_New->EditValue = $this->_New->options(false);
            $this->_New->PlaceHolder = RemoveHtml($this->_New->caption());

            // Images
            $this->Images->EditAttrs["class"] = "form-control";
            $this->Images->EditCustomAttributes = "";
            if (!EmptyValue($this->Images->Upload->DbValue)) {
                $this->Images->ImageAlt = $this->Images->alt();
                $this->Images->EditValue = $this->Images->Upload->DbValue;
            } else {
                $this->Images->EditValue = "";
            }
            if (!EmptyValue($this->Images->CurrentValue)) {
                $this->Images->Upload->FileName = $this->Images->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->Images);
            }

            // Modified

            // Edit refer script

            // Image
            $this->Image->LinkCustomAttributes = "";
            if (!EmptyValue($this->Image->Upload->DbValue)) {
                $this->Image->HrefValue = GetFileUploadUrl($this->Image, $this->Image->htmlDecode($this->Image->Upload->DbValue)); // Add prefix/suffix
                $this->Image->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->Image->HrefValue = FullUrl($this->Image->HrefValue, "href");
                }
            } else {
                $this->Image->HrefValue = "";
            }
            $this->Image->ExportHrefValue = $this->Image->UploadPath . $this->Image->Upload->DbValue;

            // Title
            $this->Title->LinkCustomAttributes = "";
            $this->Title->HrefValue = "";

            // Intro
            $this->Intro->LinkCustomAttributes = "";
            $this->Intro->HrefValue = "";

            // Content
            $this->_Content->LinkCustomAttributes = "";
            $this->_Content->HrefValue = "";

            // New
            $this->_New->LinkCustomAttributes = "";
            $this->_New->HrefValue = "";

            // Images
            $this->Images->LinkCustomAttributes = "";
            if (!EmptyValue($this->Images->Upload->DbValue)) {
                $this->Images->HrefValue = "%u"; // Add prefix/suffix
                $this->Images->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->Images->HrefValue = FullUrl($this->Images->HrefValue, "href");
                }
            } else {
                $this->Images->HrefValue = "";
            }
            $this->Images->ExportHrefValue = $this->Images->UploadPath . $this->Images->Upload->DbValue;

            // Modified
            $this->Modified->LinkCustomAttributes = "";
            $this->Modified->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->Image->Required) {
            if ($this->Image->Upload->FileName == "" && !$this->Image->Upload->KeepFile) {
                $this->Image->addErrorMessage(str_replace("%s", $this->Image->caption(), $this->Image->RequiredErrorMessage));
            }
        }
        if ($this->Title->Required) {
            if (!$this->Title->IsDetailKey && EmptyValue($this->Title->FormValue)) {
                $this->Title->addErrorMessage(str_replace("%s", $this->Title->caption(), $this->Title->RequiredErrorMessage));
            }
        }
        if ($this->Intro->Required) {
            if (!$this->Intro->IsDetailKey && EmptyValue($this->Intro->FormValue)) {
                $this->Intro->addErrorMessage(str_replace("%s", $this->Intro->caption(), $this->Intro->RequiredErrorMessage));
            }
        }
        if ($this->_Content->Required) {
            if (!$this->_Content->IsDetailKey && EmptyValue($this->_Content->FormValue)) {
                $this->_Content->addErrorMessage(str_replace("%s", $this->_Content->caption(), $this->_Content->RequiredErrorMessage));
            }
        }
        if ($this->_New->Required) {
            if ($this->_New->FormValue == "") {
                $this->_New->addErrorMessage(str_replace("%s", $this->_New->caption(), $this->_New->RequiredErrorMessage));
            }
        }
        if ($this->Images->Required) {
            if ($this->Images->Upload->FileName == "" && !$this->Images->Upload->KeepFile) {
                $this->Images->addErrorMessage(str_replace("%s", $this->Images->caption(), $this->Images->RequiredErrorMessage));
            }
        }
        if ($this->Modified->Required) {
            if (!$this->Modified->IsDetailKey && EmptyValue($this->Modified->FormValue)) {
                $this->Modified->addErrorMessage(str_replace("%s", $this->Modified->caption(), $this->Modified->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // Image
            if ($this->Image->Visible && !$this->Image->ReadOnly && !$this->Image->Upload->KeepFile) {
                $this->Image->Upload->DbValue = $rsold['Image']; // Get original value
                if ($this->Image->Upload->FileName == "") {
                    $rsnew['Image'] = null;
                } else {
                    $rsnew['Image'] = $this->Image->Upload->FileName;
                }
            }

            // Title
            $this->Title->setDbValueDef($rsnew, $this->Title->CurrentValue, "", $this->Title->ReadOnly);

            // Intro
            $this->Intro->setDbValueDef($rsnew, $this->Intro->CurrentValue, null, $this->Intro->ReadOnly);

            // Content
            $this->_Content->setDbValueDef($rsnew, $this->_Content->CurrentValue, null, $this->_Content->ReadOnly);

            // New
            $tmpBool = $this->_New->CurrentValue;
            if ($tmpBool != "1" && $tmpBool != "0") {
                $tmpBool = !empty($tmpBool) ? "1" : "0";
            }
            $this->_New->setDbValueDef($rsnew, $tmpBool, 0, $this->_New->ReadOnly);

            // Images
            if ($this->Images->Visible && !$this->Images->ReadOnly && !$this->Images->Upload->KeepFile) {
                $this->Images->Upload->DbValue = $rsold['Images']; // Get original value
                if ($this->Images->Upload->FileName == "") {
                    $rsnew['Images'] = null;
                } else {
                    $rsnew['Images'] = $this->Images->Upload->FileName;
                }
                $this->Images->ImageWidth = 930; // Resize width
                $this->Images->ImageHeight = 0; // Resize height
            }

            // Modified
            $this->Modified->CurrentValue = CurrentDateTime();
            $this->Modified->setDbValueDef($rsnew, $this->Modified->CurrentValue, CurrentDate());
            if ($this->Image->Visible && !$this->Image->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->Image->Upload->DbValue) ? [] : [$this->Image->htmlDecode($this->Image->Upload->DbValue)];
                if (!EmptyValue($this->Image->Upload->FileName)) {
                    $newFiles = [$this->Image->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->Image, $this->Image->Upload->Index);
                            if (file_exists($tempPath . $file)) {
                                if (Config("DELETE_UPLOADED_FILES")) {
                                    $oldFileFound = false;
                                    $oldFileCount = count($oldFiles);
                                    for ($j = 0; $j < $oldFileCount; $j++) {
                                        $oldFile = $oldFiles[$j];
                                        if ($oldFile == $file) { // Old file found, no need to delete anymore
                                            array_splice($oldFiles, $j, 1);
                                            $oldFileFound = true;
                                            break;
                                        }
                                    }
                                    if ($oldFileFound) { // No need to check if file exists further
                                        continue;
                                    }
                                }
                                $file1 = UniqueFilename($this->Image->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->Image->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->Image->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->Image->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->Image->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->Image->setDbValueDef($rsnew, $this->Image->Upload->FileName, null, $this->Image->ReadOnly);
                }
            }
            if ($this->Images->Visible && !$this->Images->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->Images->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Images->htmlDecode(strval($this->Images->Upload->DbValue)));
                if (!EmptyValue($this->Images->Upload->FileName)) {
                    $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), strval($this->Images->Upload->FileName));
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->Images, $this->Images->Upload->Index);
                            if (file_exists($tempPath . $file)) {
                                if (Config("DELETE_UPLOADED_FILES")) {
                                    $oldFileFound = false;
                                    $oldFileCount = count($oldFiles);
                                    for ($j = 0; $j < $oldFileCount; $j++) {
                                        $oldFile = $oldFiles[$j];
                                        if ($oldFile == $file) { // Old file found, no need to delete anymore
                                            array_splice($oldFiles, $j, 1);
                                            $oldFileFound = true;
                                            break;
                                        }
                                    }
                                    if ($oldFileFound) { // No need to check if file exists further
                                        continue;
                                    }
                                }
                                $file1 = UniqueFilename($this->Images->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->Images->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->Images->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->Images->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->Images->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->Images->setDbValueDef($rsnew, $this->Images->Upload->FileName, null, $this->Images->ReadOnly);
                }
            }

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    $editRow = $this->update($rsnew, "", $rsold);
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                    if ($this->Image->Visible && !$this->Image->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->Image->Upload->DbValue) ? [] : [$this->Image->htmlDecode($this->Image->Upload->DbValue)];
                        if (!EmptyValue($this->Image->Upload->FileName)) {
                            $newFiles = [$this->Image->Upload->FileName];
                            $newFiles2 = [$this->Image->htmlDecode($rsnew['Image'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->Image, $this->Image->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->Image->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                            $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                            return false;
                                        }
                                    }
                                }
                            }
                        } else {
                            $newFiles = [];
                        }
                        if (Config("DELETE_UPLOADED_FILES")) {
                            foreach ($oldFiles as $oldFile) {
                                if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                    @unlink($this->Image->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->Images->Visible && !$this->Images->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->Images->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Images->htmlDecode(strval($this->Images->Upload->DbValue)));
                        if (!EmptyValue($this->Images->Upload->FileName)) {
                            $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Images->Upload->FileName);
                            $newFiles2 = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->Images->htmlDecode($rsnew['Images']));
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->Images, $this->Images->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->Images->Upload->ResizeAndSaveToFile($this->Images->ImageWidth, $this->Images->ImageHeight, 100, $newFiles[$i], true, $i)) {
                                            $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                            return false;
                                        }
                                    }
                                }
                            }
                        } else {
                            $newFiles = [];
                        }
                        if (Config("DELETE_UPLOADED_FILES")) {
                            foreach ($oldFiles as $oldFile) {
                                if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                    @unlink($this->Images->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                }
            } else {
                if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($Language->phrase("UpdateCancelled"));
                }
                $editRow = false;
            }
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
            // Image
            CleanUploadTempPath($this->Image, $this->Image->Upload->Index);

            // Images
            CleanUploadTempPath($this->Images, $this->Images->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index.html");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("BlogList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x__New":
                    break;
                case "x_Enable":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
