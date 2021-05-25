<?php

namespace PHPMaker2021\inplaze;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for user
 */
class User extends DbTable
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
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $User_ID;
    public $Name;
    public $_Username;
    public $_Password;
    public $User_Level_ID;
    public $ID_Card;
    public $_Email;
    public $Active;
    public $Address;
    public $Phone;
    public $Enable;
    public $Created_Time;
    public $Modified_Time;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'user';
        $this->TableName = 'user';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`user`";
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
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // User_ID
        $this->User_ID = new DbField('user', 'user', 'x_User_ID', 'User_ID', '`User_ID`', '`User_ID`', 3, 11, -1, false, '`User_ID`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->User_ID->IsAutoIncrement = true; // Autoincrement field
        $this->User_ID->IsPrimaryKey = true; // Primary key field
        $this->User_ID->Sortable = true; // Allow sort
        $this->User_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->User_ID->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->User_ID->Param, "CustomMsg");
        $this->Fields['User_ID'] = &$this->User_ID;

        // Name
        $this->Name = new DbField('user', 'user', 'x_Name', 'Name', '`Name`', '`Name`', 200, 255, -1, false, '`Name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Name->Sortable = true; // Allow sort
        $this->Name->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Name->Param, "CustomMsg");
        $this->Fields['Name'] = &$this->Name;

        // Username
        $this->_Username = new DbField('user', 'user', 'x__Username', 'Username', '`Username`', '`Username`', 200, 255, -1, false, '`Username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_Username->Required = true; // Required field
        $this->_Username->Sortable = true; // Allow sort
        $this->_Username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_Username->Param, "CustomMsg");
        $this->Fields['Username'] = &$this->_Username;

        // Password
        $this->_Password = new DbField('user', 'user', 'x__Password', 'Password', '`Password`', '`Password`', 200, 255, -1, false, '`Password`', false, false, false, 'FORMATTED TEXT', 'PASSWORD');
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->_Password->Raw = true;
        }
        $this->_Password->Required = true; // Required field
        $this->_Password->Sortable = true; // Allow sort
        $this->_Password->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_Password->Param, "CustomMsg");
        $this->Fields['Password'] = &$this->_Password;

        // User_Level_ID
        $this->User_Level_ID = new DbField('user', 'user', 'x_User_Level_ID', 'User_Level_ID', '`User_Level_ID`', '`User_Level_ID`', 3, 11, -1, false, '`User_Level_ID`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->User_Level_ID->Nullable = false; // NOT NULL field
        $this->User_Level_ID->Required = true; // Required field
        $this->User_Level_ID->Sortable = true; // Allow sort
        $this->User_Level_ID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->User_Level_ID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->User_Level_ID->Lookup = new Lookup('User_Level_ID', 'user_level2', false, 'User_Level_ID', ["Name","","",""], [], [], [], [], [], [], '', '');
                break;
            case "th":
                $this->User_Level_ID->Lookup = new Lookup('User_Level_ID', 'user_level2', false, 'User_Level_ID', ["Name","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->User_Level_ID->Lookup = new Lookup('User_Level_ID', 'user_level2', false, 'User_Level_ID', ["Name","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->User_Level_ID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->User_Level_ID->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->User_Level_ID->Param, "CustomMsg");
        $this->Fields['User_Level_ID'] = &$this->User_Level_ID;

        // ID_Card
        $this->ID_Card = new DbField('user', 'user', 'x_ID_Card', 'ID_Card', '`ID_Card`', '`ID_Card`', 200, 255, -1, false, '`ID_Card`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ID_Card->Sortable = false; // Allow sort
        $this->ID_Card->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ID_Card->Param, "CustomMsg");
        $this->Fields['ID_Card'] = &$this->ID_Card;

        // Email
        $this->_Email = new DbField('user', 'user', 'x__Email', 'Email', '`Email`', '`Email`', 200, 255, -1, false, '`Email`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_Email->Sortable = true; // Allow sort
        $this->_Email->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_Email->Param, "CustomMsg");
        $this->Fields['Email'] = &$this->_Email;

        // Active
        $this->Active = new DbField('user', 'user', 'x_Active', 'Active', '`Active`', '`Active`', 202, 1, -1, false, '`Active`', false, false, false, 'FORMATTED TEXT', 'CHECKBOX');
        $this->Active->Sortable = false; // Allow sort
        $this->Active->DataType = DATATYPE_BOOLEAN;
        switch ($CurrentLanguage) {
            case "en":
                $this->Active->Lookup = new Lookup('Active', 'user', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            case "th":
                $this->Active->Lookup = new Lookup('Active', 'user', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->Active->Lookup = new Lookup('Active', 'user', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->Active->OptionCount = 2;
        $this->Active->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Active->Param, "CustomMsg");
        $this->Fields['Active'] = &$this->Active;

        // Address
        $this->Address = new DbField('user', 'user', 'x_Address', 'Address', '`Address`', '`Address`', 200, 255, -1, false, '`Address`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Address->Sortable = false; // Allow sort
        $this->Address->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Address->Param, "CustomMsg");
        $this->Fields['Address'] = &$this->Address;

        // Phone
        $this->Phone = new DbField('user', 'user', 'x_Phone', 'Phone', '`Phone`', '`Phone`', 200, 255, -1, false, '`Phone`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Phone->Sortable = false; // Allow sort
        $this->Phone->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Phone->Param, "CustomMsg");
        $this->Fields['Phone'] = &$this->Phone;

        // Enable
        $this->Enable = new DbField('user', 'user', 'x_Enable', 'Enable', '`Enable`', '`Enable`', 202, 1, -1, false, '`Enable`', false, false, false, 'FORMATTED TEXT', 'CHECKBOX');
        $this->Enable->Sortable = true; // Allow sort
        $this->Enable->DataType = DATATYPE_BOOLEAN;
        switch ($CurrentLanguage) {
            case "en":
                $this->Enable->Lookup = new Lookup('Enable', 'user', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            case "th":
                $this->Enable->Lookup = new Lookup('Enable', 'user', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->Enable->Lookup = new Lookup('Enable', 'user', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->Enable->OptionCount = 2;
        $this->Enable->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Enable->Param, "CustomMsg");
        $this->Fields['Enable'] = &$this->Enable;

        // Created_Time
        $this->Created_Time = new DbField('user', 'user', 'x_Created_Time', 'Created_Time', '`Created_Time`', CastDateFieldForLike("`Created_Time`", 0, "DB"), 135, 19, 0, false, '`Created_Time`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Created_Time->Sortable = true; // Allow sort
        $this->Created_Time->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Created_Time->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Created_Time->Param, "CustomMsg");
        $this->Fields['Created_Time'] = &$this->Created_Time;

        // Modified_Time
        $this->Modified_Time = new DbField('user', 'user', 'x_Modified_Time', 'Modified_Time', '`Modified_Time`', CastDateFieldForLike("`Modified_Time`", 0, "DB"), 135, 19, 0, false, '`Modified_Time`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Modified_Time->Sortable = true; // Allow sort
        $this->Modified_Time->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Modified_Time->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Modified_Time->Param, "CustomMsg");
        $this->Fields['Modified_Time'] = &$this->Modified_Time;
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

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`user`";
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
        global $Security;
        // Add User ID filter
        if ($Security->currentUserID() != "" && !$Security->isAdmin()) { // Non system admin
            $filter = $this->addUserIDFilter($filter);
        }
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            $this->User_ID->setDbValue($conn->lastInsertId());
            $rs['User_ID'] = $this->User_ID->DbValue;
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                if ($value == $this->Fields[$name]->OldValue) { // No need to update hashed password if not changed
                    continue;
                }
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            if (array_key_exists('User_ID', $rs)) {
                AddFilter($where, QuotedName('User_ID', $this->Dbid) . '=' . QuotedValue($rs['User_ID'], $this->User_ID->DataType, $this->Dbid));
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
        $this->User_ID->DbValue = $row['User_ID'];
        $this->Name->DbValue = $row['Name'];
        $this->_Username->DbValue = $row['Username'];
        $this->_Password->DbValue = $row['Password'];
        $this->User_Level_ID->DbValue = $row['User_Level_ID'];
        $this->ID_Card->DbValue = $row['ID_Card'];
        $this->_Email->DbValue = $row['Email'];
        $this->Active->DbValue = $row['Active'];
        $this->Address->DbValue = $row['Address'];
        $this->Phone->DbValue = $row['Phone'];
        $this->Enable->DbValue = $row['Enable'];
        $this->Created_Time->DbValue = $row['Created_Time'];
        $this->Modified_Time->DbValue = $row['Modified_Time'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`User_ID` = @User_ID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->User_ID->CurrentValue : $this->User_ID->OldValue;
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
                $this->User_ID->CurrentValue = $keys[0];
            } else {
                $this->User_ID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('User_ID', $row) ? $row['User_ID'] : null;
        } else {
            $val = $this->User_ID->OldValue !== null ? $this->User_ID->OldValue : $this->User_ID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@User_ID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("UserList");
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
        if ($pageName == "UserView") {
            return $Language->phrase("View");
        } elseif ($pageName == "UserEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "UserAdd") {
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
                return "UserView";
            case Config("API_ADD_ACTION"):
                return "UserAdd";
            case Config("API_EDIT_ACTION"):
                return "UserEdit";
            case Config("API_DELETE_ACTION"):
                return "UserDelete";
            case Config("API_LIST_ACTION"):
                return "UserList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "UserList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("UserView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("UserView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "UserAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "UserAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("UserEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("UserAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("UserDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "User_ID:" . JsonEncode($this->User_ID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->User_ID->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->User_ID->CurrentValue);
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
            if (($keyValue = Param("User_ID") ?? Route("User_ID")) !== null) {
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
                $this->User_ID->CurrentValue = $key;
            } else {
                $this->User_ID->OldValue = $key;
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
        $this->User_ID->setDbValue($row['User_ID']);
        $this->Name->setDbValue($row['Name']);
        $this->_Username->setDbValue($row['Username']);
        $this->_Password->setDbValue($row['Password']);
        $this->User_Level_ID->setDbValue($row['User_Level_ID']);
        $this->ID_Card->setDbValue($row['ID_Card']);
        $this->_Email->setDbValue($row['Email']);
        $this->Active->setDbValue($row['Active']);
        $this->Address->setDbValue($row['Address']);
        $this->Phone->setDbValue($row['Phone']);
        $this->Enable->setDbValue($row['Enable']);
        $this->Created_Time->setDbValue($row['Created_Time']);
        $this->Modified_Time->setDbValue($row['Modified_Time']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // User_ID
        $this->User_ID->CellCssStyle = "white-space: nowrap;";

        // Name
        $this->Name->CellCssStyle = "min-width: 100%; white-space: nowrap;";

        // Username
        $this->_Username->CellCssStyle = "white-space: nowrap;";

        // Password
        $this->_Password->CellCssStyle = "white-space: nowrap;";

        // User_Level_ID
        $this->User_Level_ID->CellCssStyle = "white-space: nowrap;";

        // ID_Card
        $this->ID_Card->CellCssStyle = "white-space: nowrap;";

        // Email
        $this->_Email->CellCssStyle = "white-space: nowrap;";

        // Active
        $this->Active->CellCssStyle = "white-space: nowrap;";

        // Address
        $this->Address->CellCssStyle = "white-space: nowrap;";

        // Phone
        $this->Phone->CellCssStyle = "white-space: nowrap;";

        // Enable
        $this->Enable->CellCssStyle = "white-space: nowrap;";

        // Created_Time
        $this->Created_Time->CellCssStyle = "white-space: nowrap;";

        // Modified_Time
        $this->Modified_Time->CellCssStyle = "white-space: nowrap;";

        // User_ID
        $this->User_ID->ViewValue = $this->User_ID->CurrentValue;
        $this->User_ID->ViewCustomAttributes = "";

        // Name
        $this->Name->ViewValue = $this->Name->CurrentValue;
        $this->Name->ViewCustomAttributes = "";

        // Username
        $this->_Username->ViewValue = $this->_Username->CurrentValue;
        $this->_Username->ViewCustomAttributes = "";

        // Password
        $this->_Password->ViewValue = $Language->phrase("PasswordMask");
        $this->_Password->ViewCustomAttributes = "";

        // User_Level_ID
        if ($Security->canAdmin()) { // System admin
            $curVal = trim(strval($this->User_Level_ID->CurrentValue));
            if ($curVal != "") {
                $this->User_Level_ID->ViewValue = $this->User_Level_ID->lookupCacheOption($curVal);
                if ($this->User_Level_ID->ViewValue === null) { // Lookup from database
                    $filterWrk = "`User_Level_ID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->User_Level_ID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->User_Level_ID->Lookup->renderViewRow($rswrk[0]);
                        $this->User_Level_ID->ViewValue = $this->User_Level_ID->displayValue($arwrk);
                    } else {
                        $this->User_Level_ID->ViewValue = $this->User_Level_ID->CurrentValue;
                    }
                }
            } else {
                $this->User_Level_ID->ViewValue = null;
            }
        } else {
            $this->User_Level_ID->ViewValue = $Language->phrase("PasswordMask");
        }
        $this->User_Level_ID->ViewCustomAttributes = "";

        // ID_Card
        $this->ID_Card->ViewValue = $this->ID_Card->CurrentValue;
        $this->ID_Card->ViewCustomAttributes = "";

        // Email
        $this->_Email->ViewValue = $this->_Email->CurrentValue;
        $this->_Email->ViewCustomAttributes = "";

        // Active
        if (ConvertToBool($this->Active->CurrentValue)) {
            $this->Active->ViewValue = $this->Active->tagCaption(1) != "" ? $this->Active->tagCaption(1) : "1";
        } else {
            $this->Active->ViewValue = $this->Active->tagCaption(2) != "" ? $this->Active->tagCaption(2) : "0";
        }
        $this->Active->ViewCustomAttributes = "";

        // Address
        $this->Address->ViewValue = $this->Address->CurrentValue;
        $this->Address->ViewCustomAttributes = "";

        // Phone
        $this->Phone->ViewValue = $this->Phone->CurrentValue;
        $this->Phone->ViewCustomAttributes = "";

        // Enable
        if (ConvertToBool($this->Enable->CurrentValue)) {
            $this->Enable->ViewValue = $this->Enable->tagCaption(1) != "" ? $this->Enable->tagCaption(1) : "ใช้งาน";
        } else {
            $this->Enable->ViewValue = $this->Enable->tagCaption(2) != "" ? $this->Enable->tagCaption(2) : "ระงับ";
        }
        $this->Enable->ViewCustomAttributes = "";

        // Created_Time
        $this->Created_Time->ViewValue = $this->Created_Time->CurrentValue;
        $this->Created_Time->ViewValue = FormatDateTime($this->Created_Time->ViewValue, 0);
        $this->Created_Time->ViewCustomAttributes = "";

        // Modified_Time
        $this->Modified_Time->ViewValue = $this->Modified_Time->CurrentValue;
        $this->Modified_Time->ViewValue = FormatDateTime($this->Modified_Time->ViewValue, 0);
        $this->Modified_Time->ViewCustomAttributes = "";

        // User_ID
        $this->User_ID->LinkCustomAttributes = "";
        $this->User_ID->HrefValue = "";
        $this->User_ID->TooltipValue = "";

        // Name
        $this->Name->LinkCustomAttributes = "";
        $this->Name->HrefValue = "";
        $this->Name->TooltipValue = "";

        // Username
        $this->_Username->LinkCustomAttributes = "";
        $this->_Username->HrefValue = "";
        $this->_Username->TooltipValue = "";

        // Password
        $this->_Password->LinkCustomAttributes = "";
        $this->_Password->HrefValue = "";
        $this->_Password->TooltipValue = "";

        // User_Level_ID
        $this->User_Level_ID->LinkCustomAttributes = "";
        $this->User_Level_ID->HrefValue = "";
        $this->User_Level_ID->TooltipValue = "";

        // ID_Card
        $this->ID_Card->LinkCustomAttributes = "";
        $this->ID_Card->HrefValue = "";
        $this->ID_Card->TooltipValue = "";

        // Email
        $this->_Email->LinkCustomAttributes = "";
        $this->_Email->HrefValue = "";
        $this->_Email->TooltipValue = "";

        // Active
        $this->Active->LinkCustomAttributes = "";
        $this->Active->HrefValue = "";
        $this->Active->TooltipValue = "";

        // Address
        $this->Address->LinkCustomAttributes = "";
        $this->Address->HrefValue = "";
        $this->Address->TooltipValue = "";

        // Phone
        $this->Phone->LinkCustomAttributes = "";
        $this->Phone->HrefValue = "";
        $this->Phone->TooltipValue = "";

        // Enable
        $this->Enable->LinkCustomAttributes = "";
        $this->Enable->HrefValue = "";
        $this->Enable->TooltipValue = "";

        // Created_Time
        $this->Created_Time->LinkCustomAttributes = "";
        $this->Created_Time->HrefValue = "";
        $this->Created_Time->TooltipValue = "";

        // Modified_Time
        $this->Modified_Time->LinkCustomAttributes = "";
        $this->Modified_Time->HrefValue = "";
        $this->Modified_Time->TooltipValue = "";

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

        // User_ID
        $this->User_ID->EditAttrs["class"] = "form-control";
        $this->User_ID->EditCustomAttributes = "";
        $this->User_ID->EditValue = $this->User_ID->CurrentValue;
        $this->User_ID->ViewCustomAttributes = "";

        // Name
        $this->Name->EditAttrs["class"] = "form-control";
        $this->Name->EditCustomAttributes = "";
        if (!$this->Name->Raw) {
            $this->Name->CurrentValue = HtmlDecode($this->Name->CurrentValue);
        }
        $this->Name->EditValue = $this->Name->CurrentValue;
        $this->Name->PlaceHolder = RemoveHtml($this->Name->caption());

        // Username
        $this->_Username->EditAttrs["class"] = "form-control";
        $this->_Username->EditCustomAttributes = "";
        if (!$this->_Username->Raw) {
            $this->_Username->CurrentValue = HtmlDecode($this->_Username->CurrentValue);
        }
        $this->_Username->EditValue = $this->_Username->CurrentValue;
        $this->_Username->PlaceHolder = RemoveHtml($this->_Username->caption());

        // Password
        $this->_Password->EditAttrs["class"] = "form-control";
        $this->_Password->EditCustomAttributes = "";
        $this->_Password->EditValue = $Language->phrase("PasswordMask"); // Show as masked password
        $this->_Password->PlaceHolder = RemoveHtml($this->_Password->caption());

        // User_Level_ID
        $this->User_Level_ID->EditAttrs["class"] = "form-control";
        $this->User_Level_ID->EditCustomAttributes = "";
        if (!$Security->canAdmin()) { // System admin
            $this->User_Level_ID->EditValue = $Language->phrase("PasswordMask");
        } else {
            $this->User_Level_ID->PlaceHolder = RemoveHtml($this->User_Level_ID->caption());
        }

        // ID_Card
        $this->ID_Card->EditAttrs["class"] = "form-control";
        $this->ID_Card->EditCustomAttributes = "";
        if (!$this->ID_Card->Raw) {
            $this->ID_Card->CurrentValue = HtmlDecode($this->ID_Card->CurrentValue);
        }
        $this->ID_Card->EditValue = $this->ID_Card->CurrentValue;
        $this->ID_Card->PlaceHolder = RemoveHtml($this->ID_Card->caption());

        // Email
        $this->_Email->EditAttrs["class"] = "form-control";
        $this->_Email->EditCustomAttributes = "";
        if (!$this->_Email->Raw) {
            $this->_Email->CurrentValue = HtmlDecode($this->_Email->CurrentValue);
        }
        $this->_Email->EditValue = $this->_Email->CurrentValue;
        $this->_Email->PlaceHolder = RemoveHtml($this->_Email->caption());

        // Active
        $this->Active->EditCustomAttributes = "";
        $this->Active->EditValue = $this->Active->options(false);
        $this->Active->PlaceHolder = RemoveHtml($this->Active->caption());

        // Address
        $this->Address->EditAttrs["class"] = "form-control";
        $this->Address->EditCustomAttributes = "";
        if (!$this->Address->Raw) {
            $this->Address->CurrentValue = HtmlDecode($this->Address->CurrentValue);
        }
        $this->Address->EditValue = $this->Address->CurrentValue;
        $this->Address->PlaceHolder = RemoveHtml($this->Address->caption());

        // Phone
        $this->Phone->EditAttrs["class"] = "form-control";
        $this->Phone->EditCustomAttributes = "";
        if (!$this->Phone->Raw) {
            $this->Phone->CurrentValue = HtmlDecode($this->Phone->CurrentValue);
        }
        $this->Phone->EditValue = $this->Phone->CurrentValue;
        $this->Phone->PlaceHolder = RemoveHtml($this->Phone->caption());

        // Enable
        $this->Enable->EditCustomAttributes = "";
        $this->Enable->EditValue = $this->Enable->options(false);
        $this->Enable->PlaceHolder = RemoveHtml($this->Enable->caption());

        // Created_Time

        // Modified_Time

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
                } else {
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
                    } else {
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

    // User ID filter
    public function getUserIDFilter($userId)
    {
        $userIdFilter = '`User_ID` = ' . QuotedValue($userId, DATATYPE_NUMBER, Config("USER_TABLE_DBID"));
        return $userIdFilter;
    }

    // Add User ID filter
    public function addUserIDFilter($filter = "")
    {
        global $Security;
        $filterWrk = "";
        $id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
        if (!$this->userIDAllow($id) && !$Security->isAdmin()) {
            $filterWrk = $Security->userIdList();
            if ($filterWrk != "") {
                $filterWrk = '`User_ID` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIdFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM `user`";
        $filter = $this->addUserIDFilter("");
        if ($filter != "") {
            $sql .= " WHERE " . $filter;
        }

        // List all values
        if ($rs = Conn($UserTable->Dbid)->executeQuery($sql)->fetchAll(\PDO::FETCH_NUM)) {
            foreach ($rs as $row) {
                if ($wrk != "") {
                    $wrk .= ",";
                }
                $wrk .= QuotedValue($row[0], $masterfld->DataType, Config("USER_TABLE_DBID"));
            }
        }
        if ($wrk != "") {
            $wrk = $fld->Expression . " IN (" . $wrk . ")";
        } else { // No User ID value found
            $wrk = "0=1";
        }
        return $wrk;
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
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

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
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
