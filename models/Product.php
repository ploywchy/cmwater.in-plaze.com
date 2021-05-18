<?php

namespace PHPMaker2021\inplaze;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for product
 */
class Product extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-3 col-form-label ew-label";
    public $RightColumnClass = "col-sm-9";
    public $OffsetColumnClass = "col-sm-9 offset-sm-3";
    public $TableLeftColumnClass = "w-col-3";

    // Export
    public $ExportDoc;

    // Fields
    public $Product_ID;
    public $Category_ID;
    public $Name;
    public $Intro;
    public $Description;
    public $Price;
    public $Priority;
    public $_New;
    public $Image;
    public $View;
    public $Enable;
    public $Images;
    public $Created;
    public $Modified;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'product';
        $this->TableName = 'product';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`product`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // Product_ID
        $this->Product_ID = new DbField('product', 'product', 'x_Product_ID', 'Product_ID', '`Product_ID`', '`Product_ID`', 3, 11, -1, false, '`Product_ID`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->Product_ID->IsAutoIncrement = true; // Autoincrement field
        $this->Product_ID->IsPrimaryKey = true; // Primary key field
        $this->Product_ID->Sortable = false; // Allow sort
        $this->Product_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Product_ID'] = &$this->Product_ID;

        // Category_ID
        $this->Category_ID = new DbField('product', 'product', 'x_Category_ID', 'Category_ID', '`Category_ID`', '`Category_ID`', 3, 11, -1, false, '`Category_ID`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->Category_ID->IsForeignKey = true; // Foreign key field
        $this->Category_ID->Nullable = false; // NOT NULL field
        $this->Category_ID->Required = true; // Required field
        $this->Category_ID->Sortable = true; // Allow sort
        $this->Category_ID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Category_ID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->Category_ID->Lookup = new Lookup('Category_ID', 'category', false, 'Category_ID', ["Name","","",""], [], [], [], [], [], [], '`Name`', '');
                break;
            case "th":
                $this->Category_ID->Lookup = new Lookup('Category_ID', 'category', false, 'Category_ID', ["Name","","",""], [], [], [], [], [], [], '`Name`', '');
                break;
            default:
                $this->Category_ID->Lookup = new Lookup('Category_ID', 'category', false, 'Category_ID', ["Name","","",""], [], [], [], [], [], [], '`Name`', '');
                break;
        }
        $this->Category_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Category_ID'] = &$this->Category_ID;

        // Name
        $this->Name = new DbField('product', 'product', 'x_Name', 'Name', '`Name`', '`Name`', 200, 255, -1, false, '`Name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Name->Nullable = false; // NOT NULL field
        $this->Name->Required = true; // Required field
        $this->Name->Sortable = true; // Allow sort
        $this->Fields['Name'] = &$this->Name;

        // Intro
        $this->Intro = new DbField('product', 'product', 'x_Intro', 'Intro', '`Intro`', '`Intro`', 201, 65535, -1, false, '`Intro`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->Intro->Sortable = true; // Allow sort
        $this->Fields['Intro'] = &$this->Intro;

        // Description
        $this->Description = new DbField('product', 'product', 'x_Description', 'Description', '`Description`', '`Description`', 201, 65535, -1, false, '`Description`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->Description->Sortable = true; // Allow sort
        $this->Fields['Description'] = &$this->Description;

        // Price
        $this->Price = new DbField('product', 'product', 'x_Price', 'Price', '`Price`', '`Price`', 131, 15, -1, false, '`Price`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Price->Sortable = false; // Allow sort
        $this->Price->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Price'] = &$this->Price;

        // Priority
        $this->Priority = new DbField('product', 'product', 'x_Priority', 'Priority', '`Priority`', '`Priority`', 3, 5, -1, false, '`Priority`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Priority->Nullable = false; // NOT NULL field
        $this->Priority->Required = true; // Required field
        $this->Priority->Sortable = false; // Allow sort
        $this->Priority->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Priority'] = &$this->Priority;

        // New
        $this->_New = new DbField('product', 'product', 'x__New', 'New', '`New`', '`New`', 202, 1, -1, false, '`New`', false, false, false, 'FORMATTED TEXT', 'CHECKBOX');
        $this->_New->Nullable = false; // NOT NULL field
        $this->_New->Sortable = false; // Allow sort
        $this->_New->DataType = DATATYPE_BOOLEAN;
        switch ($CurrentLanguage) {
            case "en":
                $this->_New->Lookup = new Lookup('New', 'product', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            case "th":
                $this->_New->Lookup = new Lookup('New', 'product', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->_New->Lookup = new Lookup('New', 'product', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->_New->OptionCount = 2;
        $this->Fields['New'] = &$this->_New;

        // Image
        $this->Image = new DbField('product', 'product', 'x_Image', 'Image', '`Image`', '`Image`', 200, 100, -1, true, '`Image`', false, false, false, 'IMAGE', 'FILE');
        $this->Image->Sortable = true; // Allow sort
        $this->Fields['Image'] = &$this->Image;

        // View
        $this->View = new DbField('product', 'product', 'x_View', 'View', '`View`', '`View`', 3, 6, -1, false, '`View`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->View->Nullable = false; // NOT NULL field
        $this->View->Required = true; // Required field
        $this->View->Sortable = false; // Allow sort
        $this->View->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['View'] = &$this->View;

        // Enable
        $this->Enable = new DbField('product', 'product', 'x_Enable', 'Enable', '`Enable`', '`Enable`', 202, 1, -1, false, '`Enable`', false, false, false, 'FORMATTED TEXT', 'CHECKBOX');
        $this->Enable->Sortable = false; // Allow sort
        $this->Enable->DataType = DATATYPE_BOOLEAN;
        switch ($CurrentLanguage) {
            case "en":
                $this->Enable->Lookup = new Lookup('Enable', 'product', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            case "th":
                $this->Enable->Lookup = new Lookup('Enable', 'product', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->Enable->Lookup = new Lookup('Enable', 'product', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->Enable->OptionCount = 2;
        $this->Fields['Enable'] = &$this->Enable;

        // Images
        $this->Images = new DbField('product', 'product', 'x_Images', 'Images', '`Images`', '`Images`', 201, 65535, -1, true, '`Images`', false, false, false, 'IMAGE', 'FILE');
        $this->Images->Sortable = false; // Allow sort
        $this->Images->UploadMultiple = true;
        $this->Images->Upload->UploadMultiple = true;
        $this->Images->UploadMaxFileCount = 0;
        $this->Fields['Images'] = &$this->Images;

        // Created
        $this->Created = new DbField('product', 'product', 'x_Created', 'Created', '`Created`', CastDateFieldForLike("`Created`", 0, "DB"), 135, 19, 0, false, '`Created`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Created->Nullable = false; // NOT NULL field
        $this->Created->Sortable = true; // Allow sort
        $this->Created->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['Created'] = &$this->Created;

        // Modified
        $this->Modified = new DbField('product', 'product', 'x_Modified', 'Modified', '`Modified`', CastDateFieldForLike("`Modified`", 0, "DB"), 135, 19, 0, false, '`Modified`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Modified->Nullable = false; // NOT NULL field
        $this->Modified->Sortable = true; // Allow sort
        $this->Modified->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['Modified'] = &$this->Modified;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        } else {
            $fld->setSort("");
        }
    }

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Session master WHERE clause
    public function getMasterFilter()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "category") {
            if ($this->Category_ID->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`Category_ID`", $this->Category_ID->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Session detail WHERE clause
    public function getDetailFilter()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "category") {
            if ($this->Category_ID->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`Category_ID`", $this->Category_ID->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_category()
    {
        return "`Category_ID`=@Category_ID@";
    }
    // Detail filter
    public function sqlDetailFilter_category()
    {
        return "`Category_ID`=@Category_ID@";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`product`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->Product_ID->setDbValue($conn->lastInsertId());
            $rs['Product_ID'] = $this->Product_ID->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('Product_ID', $rs)) {
                AddFilter($where, QuotedName('Product_ID', $this->Dbid) . '=' . QuotedValue($rs['Product_ID'], $this->Product_ID->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->Product_ID->DbValue = $row['Product_ID'];
        $this->Category_ID->DbValue = $row['Category_ID'];
        $this->Name->DbValue = $row['Name'];
        $this->Intro->DbValue = $row['Intro'];
        $this->Description->DbValue = $row['Description'];
        $this->Price->DbValue = $row['Price'];
        $this->Priority->DbValue = $row['Priority'];
        $this->_New->DbValue = $row['New'];
        $this->Image->Upload->DbValue = $row['Image'];
        $this->View->DbValue = $row['View'];
        $this->Enable->DbValue = $row['Enable'];
        $this->Images->Upload->DbValue = $row['Images'];
        $this->Created->DbValue = $row['Created'];
        $this->Modified->DbValue = $row['Modified'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['Image']) ? [] : [$row['Image']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->Image->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->Image->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['Images']) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $row['Images']);
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->Images->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->Images->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`Product_ID` = @Product_ID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->Product_ID->CurrentValue : $this->Product_ID->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->Product_ID->CurrentValue = $keys[0];
            } else {
                $this->Product_ID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('Product_ID', $row) ? $row['Product_ID'] : null;
        } else {
            $val = $this->Product_ID->OldValue !== null ? $this->Product_ID->OldValue : $this->Product_ID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@Product_ID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("ProductList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "ProductView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ProductEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ProductAdd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "ProductView";
            case Config("API_ADD_ACTION"):
                return "ProductAdd";
            case Config("API_EDIT_ACTION"):
                return "ProductEdit";
            case Config("API_DELETE_ACTION"):
                return "ProductDelete";
            case Config("API_LIST_ACTION"):
                return "ProductList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ProductList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ProductView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ProductView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ProductAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ProductAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ProductEdit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ProductAdd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("ProductDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "category" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_Category_ID", $this->Category_ID->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "Product_ID:" . JsonEncode($this->Product_ID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->Product_ID->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->Product_ID->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 1);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("Product_ID") ?? Route("Product_ID")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->Product_ID->CurrentValue = $key;
            } else {
                $this->Product_ID->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->Product_ID->setDbValue($row['Product_ID']);
        $this->Category_ID->setDbValue($row['Category_ID']);
        $this->Name->setDbValue($row['Name']);
        $this->Intro->setDbValue($row['Intro']);
        $this->Description->setDbValue($row['Description']);
        $this->Price->setDbValue($row['Price']);
        $this->Priority->setDbValue($row['Priority']);
        $this->_New->setDbValue($row['New']);
        $this->Image->Upload->DbValue = $row['Image'];
        $this->View->setDbValue($row['View']);
        $this->Enable->setDbValue($row['Enable']);
        $this->Images->Upload->DbValue = $row['Images'];
        $this->Created->setDbValue($row['Created']);
        $this->Modified->setDbValue($row['Modified']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // Product_ID
        $this->Product_ID->CellCssStyle = "white-space: nowrap;";

        // Category_ID
        $this->Category_ID->CellCssStyle = "white-space: nowrap;";

        // Name
        $this->Name->CellCssStyle = "white-space: nowrap;";

        // Intro

        // Description

        // Price
        $this->Price->CellCssStyle = "white-space: nowrap;";

        // Priority
        $this->Priority->CellCssStyle = "white-space: nowrap;";

        // New
        $this->_New->CellCssStyle = "white-space: nowrap;";

        // Image
        $this->Image->CellCssStyle = "white-space: nowrap;";

        // View
        $this->View->CellCssStyle = "white-space: nowrap;";

        // Enable
        $this->Enable->CellCssStyle = "white-space: nowrap;";

        // Images
        $this->Images->CellCssStyle = "white-space: nowrap;";

        // Created
        $this->Created->CellCssStyle = "white-space: nowrap;";

        // Modified
        $this->Modified->CellCssStyle = "white-space: nowrap;";

        // Product_ID
        $this->Product_ID->ViewValue = $this->Product_ID->CurrentValue;
        $this->Product_ID->ViewCustomAttributes = "";

        // Category_ID
        $curVal = strval($this->Category_ID->CurrentValue);
        if ($curVal != "") {
            $this->Category_ID->ViewValue = $this->Category_ID->lookupCacheOption($curVal);
            if ($this->Category_ID->ViewValue === null) { // Lookup from database
                $filterWrk = "`Category_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->Category_ID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->Category_ID->Lookup->renderViewRow($rswrk[0]);
                    $this->Category_ID->ViewValue = $this->Category_ID->displayValue($arwrk);
                } else {
                    $this->Category_ID->ViewValue = $this->Category_ID->CurrentValue;
                }
            }
        } else {
            $this->Category_ID->ViewValue = null;
        }
        $this->Category_ID->ViewCustomAttributes = "";

        // Name
        $this->Name->ViewValue = $this->Name->CurrentValue;
        $this->Name->ViewCustomAttributes = "";

        // Intro
        $this->Intro->ViewValue = $this->Intro->CurrentValue;
        $this->Intro->ViewCustomAttributes = "";

        // Description
        $this->Description->ViewValue = $this->Description->CurrentValue;
        $this->Description->ViewCustomAttributes = "";

        // Price
        $this->Price->ViewValue = $this->Price->CurrentValue;
        $this->Price->ViewValue = FormatNumber($this->Price->ViewValue, 0, -2, -2, -2);
        $this->Price->ViewCustomAttributes = "";

        // Priority
        $this->Priority->ViewValue = $this->Priority->CurrentValue;
        $this->Priority->ViewValue = FormatNumber($this->Priority->ViewValue, 0, -2, -2, -2);
        $this->Priority->ViewCustomAttributes = "";

        // New
        if (ConvertToBool($this->_New->CurrentValue)) {
            $this->_New->ViewValue = $this->_New->tagCaption(2) != "" ? $this->_New->tagCaption(2) : "Yes";
        } else {
            $this->_New->ViewValue = $this->_New->tagCaption(1) != "" ? $this->_New->tagCaption(1) : "No";
        }
        $this->_New->ViewCustomAttributes = "";

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

        // View
        $this->View->ViewValue = $this->View->CurrentValue;
        $this->View->ViewValue = FormatNumber($this->View->ViewValue, 0, -2, -2, -2);
        $this->View->CellCssStyle .= "text-align: right;";
        $this->View->ViewCustomAttributes = "";

        // Enable
        if (ConvertToBool($this->Enable->CurrentValue)) {
            $this->Enable->ViewValue = $this->Enable->tagCaption(2) != "" ? $this->Enable->tagCaption(2) : "1";
        } else {
            $this->Enable->ViewValue = $this->Enable->tagCaption(1) != "" ? $this->Enable->tagCaption(1) : "0";
        }
        $this->Enable->ViewCustomAttributes = "";

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

        // Product_ID
        $this->Product_ID->LinkCustomAttributes = "";
        $this->Product_ID->HrefValue = "";
        $this->Product_ID->TooltipValue = "";

        // Category_ID
        $this->Category_ID->LinkCustomAttributes = "";
        $this->Category_ID->HrefValue = "";
        $this->Category_ID->TooltipValue = "";

        // Name
        $this->Name->LinkCustomAttributes = "";
        $this->Name->HrefValue = "";
        $this->Name->TooltipValue = "";

        // Intro
        $this->Intro->LinkCustomAttributes = "";
        $this->Intro->HrefValue = "";
        $this->Intro->TooltipValue = "";

        // Description
        $this->Description->LinkCustomAttributes = "";
        $this->Description->HrefValue = "";
        $this->Description->TooltipValue = "";

        // Price
        $this->Price->LinkCustomAttributes = "";
        $this->Price->HrefValue = "";
        $this->Price->TooltipValue = "";

        // Priority
        $this->Priority->LinkCustomAttributes = "";
        $this->Priority->HrefValue = "";
        $this->Priority->TooltipValue = "";

        // New
        $this->_New->LinkCustomAttributes = "";
        $this->_New->HrefValue = "";
        $this->_New->TooltipValue = "";

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
            $this->Image->LinkAttrs["data-rel"] = "product_x_Image";
            $this->Image->LinkAttrs->appendClass("ew-lightbox");
        }

        // View
        $this->View->LinkCustomAttributes = "";
        $this->View->HrefValue = "";
        $this->View->TooltipValue = "";

        // Enable
        $this->Enable->LinkCustomAttributes = "";
        $this->Enable->HrefValue = "";
        $this->Enable->TooltipValue = "";

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
            $this->Images->LinkAttrs["data-rel"] = "product_x_Images";
            $this->Images->LinkAttrs->appendClass("ew-lightbox");
        }

        // Created
        $this->Created->LinkCustomAttributes = "";
        $this->Created->HrefValue = "";
        $this->Created->TooltipValue = "";

        // Modified
        $this->Modified->LinkCustomAttributes = "";
        $this->Modified->HrefValue = "";
        $this->Modified->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Product_ID
        $this->Product_ID->EditAttrs["class"] = "form-control";
        $this->Product_ID->EditCustomAttributes = "";
        $this->Product_ID->EditValue = $this->Product_ID->CurrentValue;
        $this->Product_ID->ViewCustomAttributes = "";

        // Category_ID
        $this->Category_ID->EditAttrs["class"] = "form-control";
        $this->Category_ID->EditCustomAttributes = "";
        if ($this->Category_ID->getSessionValue() != "") {
            $this->Category_ID->CurrentValue = GetForeignKeyValue($this->Category_ID->getSessionValue());
            $curVal = strval($this->Category_ID->CurrentValue);
            if ($curVal != "") {
                $this->Category_ID->ViewValue = $this->Category_ID->lookupCacheOption($curVal);
                if ($this->Category_ID->ViewValue === null) { // Lookup from database
                    $filterWrk = "`Category_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->Category_ID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Category_ID->Lookup->renderViewRow($rswrk[0]);
                        $this->Category_ID->ViewValue = $this->Category_ID->displayValue($arwrk);
                    } else {
                        $this->Category_ID->ViewValue = $this->Category_ID->CurrentValue;
                    }
                }
            } else {
                $this->Category_ID->ViewValue = null;
            }
            $this->Category_ID->ViewCustomAttributes = "";
        } else {
            $this->Category_ID->PlaceHolder = RemoveHtml($this->Category_ID->caption());
        }

        // Name
        $this->Name->EditAttrs["class"] = "form-control";
        $this->Name->EditCustomAttributes = "";
        if (!$this->Name->Raw) {
            $this->Name->CurrentValue = HtmlDecode($this->Name->CurrentValue);
        }
        $this->Name->EditValue = $this->Name->CurrentValue;
        $this->Name->PlaceHolder = RemoveHtml($this->Name->caption());

        // Intro
        $this->Intro->EditAttrs["class"] = "form-control";
        $this->Intro->EditCustomAttributes = "";
        $this->Intro->EditValue = $this->Intro->CurrentValue;
        $this->Intro->PlaceHolder = RemoveHtml($this->Intro->caption());

        // Description
        $this->Description->EditAttrs["class"] = "form-control";
        $this->Description->EditCustomAttributes = "";
        $this->Description->EditValue = $this->Description->CurrentValue;
        $this->Description->PlaceHolder = RemoveHtml($this->Description->caption());

        // Price
        $this->Price->EditAttrs["class"] = "form-control";
        $this->Price->EditCustomAttributes = "";
        $this->Price->EditValue = $this->Price->CurrentValue;
        $this->Price->PlaceHolder = RemoveHtml($this->Price->caption());
        if (strval($this->Price->EditValue) != "" && is_numeric($this->Price->EditValue)) {
            $this->Price->EditValue = FormatNumber($this->Price->EditValue, -2, -2, -2, -2);
        }

        // Priority
        $this->Priority->EditAttrs["class"] = "form-control";
        $this->Priority->EditCustomAttributes = "";
        $this->Priority->EditValue = $this->Priority->CurrentValue;
        $this->Priority->PlaceHolder = RemoveHtml($this->Priority->caption());

        // New
        $this->_New->EditCustomAttributes = "";
        $this->_New->EditValue = $this->_New->options(false);
        $this->_New->PlaceHolder = RemoveHtml($this->_New->caption());

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

        // View
        $this->View->EditAttrs["class"] = "form-control";
        $this->View->EditCustomAttributes = "";
        $this->View->EditValue = $this->View->CurrentValue;
        $this->View->EditValue = FormatNumber($this->View->EditValue, 0, -2, -2, -2);
        $this->View->CellCssStyle .= "text-align: right;";
        $this->View->ViewCustomAttributes = "";

        // Enable
        $this->Enable->EditCustomAttributes = "";
        $this->Enable->EditValue = $this->Enable->options(false);
        $this->Enable->PlaceHolder = RemoveHtml($this->Enable->caption());

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

        // Created

        // Modified

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->Category_ID);
                    $doc->exportCaption($this->Name);
                    $doc->exportCaption($this->Intro);
                    $doc->exportCaption($this->Description);
                    $doc->exportCaption($this->Price);
                    $doc->exportCaption($this->Image);
                    $doc->exportCaption($this->Created);
                    $doc->exportCaption($this->Modified);
                } else {
                    $doc->exportCaption($this->Category_ID);
                    $doc->exportCaption($this->Name);
                    $doc->exportCaption($this->Image);
                    $doc->exportCaption($this->Created);
                    $doc->exportCaption($this->Modified);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->Category_ID);
                        $doc->exportField($this->Name);
                        $doc->exportField($this->Intro);
                        $doc->exportField($this->Description);
                        $doc->exportField($this->Price);
                        $doc->exportField($this->Image);
                        $doc->exportField($this->Created);
                        $doc->exportField($this->Modified);
                    } else {
                        $doc->exportField($this->Category_ID);
                        $doc->exportField($this->Name);
                        $doc->exportField($this->Image);
                        $doc->exportField($this->Created);
                        $doc->exportField($this->Modified);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'Image') {
            $fldName = "Image";
            $fileNameFld = "Image";
        } elseif ($fldparm == 'Images') {
            $fldName = "Images";
            $fileNameFld = "Images";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->Product_ID->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssoc($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, 100, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    public function rowInserted($rsold, &$rsnew) {
    	if (empty($_SESSION['HTTP_REFERER'])) {
    		CurrentPage()->terminate('home.php');
    	} else {
    		CurrentPage()->terminate($_SESSION['HTTP_REFERER']);
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    public function rowUpdated($rsold, &$rsnew) {
    	if (empty($_SESSION['HTTP_REFERER'])) {
    		CurrentPage()->terminate('home.php');
    	} else {
    		CurrentPage()->terminate($_SESSION['HTTP_REFERER']);
    	}
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
