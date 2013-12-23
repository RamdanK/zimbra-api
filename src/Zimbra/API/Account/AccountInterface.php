<?php
/**
 * This file is part of the Zimbra API in PHP library.
 *
 * © Nguyen Van Nguyen <nguyennv1981@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zimbra\API\Account;

use Zimbra\Soap\Struct\AccountSelector as Account;
use Zimbra\Soap\Struct\AuthToken;
use Zimbra\Soap\Struct\CursorInfo;
use Zimbra\Soap\Struct\DistributionListSelector as DistListSelector;
use Zimbra\Soap\Struct\DistributionListAction as DistListAction;
use Zimbra\Soap\Struct\EntrySearchFilterInfo as SearchFilter;
use Zimbra\Soap\Struct\GranteeChooser;
use Zimbra\Soap\Struct\Identity;
use Zimbra\Soap\Struct\NameId;
use Zimbra\Soap\Struct\PreAuth;
use Zimbra\Soap\Struct\Signature;

/**
 * AccountInterface is a interface which allows to connect Zimbra API account functions via SOAP
 * @package   Zimbra
 * @category  API
 * @author    Nguyen Van Nguyen - nguyennv1981@gmail.com
 * @copyright Copyright © 2013 by Nguyen Van Nguyen.
 */
interface AccountInterface
{
    /**
     * Authenticate for an account
     *
     * @param  Account   $account The user account.
     * @param  string    $password The user password.
     * @param  PreAuth   $key Pre authentication key
     * @param  AuthToken $token The authentication token.
     * @param  string    $virtualHost If specified (in conjunction with by="name"), virtual-host is used to determine the domain of the account name, if it does not include a domain component.
     * @param  array     $prefs Preference.
     * @param  array     $attrs The attributes.
     * @param  string    $requestedSkin If specified the name of the skin requested by the client.
     * @param  string    $persistAuthTokenCookie Controls whether the auth token cookie in the response should be persisted when the browser exits.
     * @return authentication token
     */
    function auth(
        Account $account = null,
        $password = null,
        PreAuth $preauth = null,
        AuthToken $authToken = null,
        $virtualHost = null,
        array $prefs = array(),
        array $attrs = array(),
        $requestedSkin = null,
        $persistAuthTokenCookie = null
    );

    /**
     * Authenticate for an account
     *
     * @param  Account $account  The user account.
     * @param  string $password    The user password.
     * @param  string $virtualHost If specified (in conjunction with by="name"), virtual-host is used to determine the domain of the account name, if it does not include a domain component.
     * @return authentication token
     */
    function authByAcount(
        Account $account,
        $password,
        $virtualHost = null
    );

    /**
     * Authenticate for an account by token
     *
     * @param  Account $account The user account.
     * @param  AuthToken $token The authentication token.
     * @param  string $virtualHost If specified (in conjunction with by="name"), virtual-host is used to determine the domain of the account name, if it does not include a domain component.
     * @return authentication token
     */
    function authByToken(
        Account $account,
        AuthToken $token,
        $virtualHost = null
    );

    /**
     * Authenticate for an account by pre authentication key
     *
     * @param  Account $account The user account.
     * @param  string $key    Pre authentication key
     * @param  string $virtualHost If specified (in conjunction with by="name"), virtual-host is used to determine the domain of the account name, if it does not include a domain component.
     * @return authentication token
     */
    function authByPre(
        Account $account,
        $key,
        $virtualHost = null
    );

    /**
     * Perform an autocomplete for a name against the Global Address List
     *
     * @param  string $name      The name to test for autocompletion
     * @param  bool   $needExp   Flag whether the {exp} flag is needed in the response for group entries.
     * @param  string $type      Type of addresses to auto-complete on
     * @param  string $galAcctId GAL Account ID
     * @param  int    $limit An  integer specifying the maximum number of results to return
     * @return mixed
     */
    function autoCompleteGal(
        $name,
        $needExp = null,
        $type = null,
        $galAcctId = null,
        $limit = null
    );

    /**
     * Change password
     *
     * @param  Account $account The user account.
     * @param  string $oldPassword Old password
     * @param  string $password    New Password to assign
     * @param  string $virtualHost Virtual-host is used to determine the domain of the account name
     * @return mixed
     */
    function changePassword(
        Account $account,
        $oldPassword,
        $password,
        $virtualHost = ''
    );

    /**
     * Check if the authed user has the specified right(s) on a target.
     *
     * @param  array $targets Array of CheckRightsTargetSpec.
     * @return mixed
     */
    function checkRights(array $targets);

    /**
     * Create a Distribution List
     * Note: authed account must have the privilege to create dist lists in the domain
     *
     * @param  string $name Name for the new Distribution List
     * @param  bool   $dynamic Flag type of distribution list to create
     * @param  array  $attrs Attributes specified as key value pairs
     * @return mixed
     */
    function createDistributionList(
        $name,
        $dynamic = null,
        array $attrs = array()
    );

    /**
     * Create an Identity
     *
     * @param  Identity $identity Specify identity to be modified Must specify either "name" or "id" attribute
     * @return mixed
     */
    function createIdentity(Identity $identity);

    /**
     * Create a signature
     *
     * @param  Signature $signature Details of the signature to be created
     * @return mixed
     */
    function createSignature(Signature $signature);

    /**
     * Delete an Identity
     *
     * @param  NameId $identity Details of the identity to delete
     * @return mixed
     */
    function deleteIdentity(NameId $identity);

    /**
     * Delete a signature
     *
     * @param  NameId $signature Details of the signature to delete
     * @return mixed
     */
    function deleteSignature(NameId $signature);

    /**
     * Return all targets of the specified rights applicable to the requested account
     *
     * @param  array $rights The rights.
     * @return mixed
     */
    function discoverRights(array $rights);

    /**
     * Perform an action on a Distribution List 
     * Notes:
     *   1. Authorized account must be one of the list owners
     *   2. For owners/rights, only grants on the group itself will be modified,
     *      grants on domain and globalgrant (from which the right can be inherited) will not be touched.
     *      Only admins can modify grants on domains and globalgrant,
     *      owners of groups can only modify grants on the group entry.
     *
     * @param  DistListSelector $dl Identifies the distribution list to act upon
     * @param  DistListAction $action Specifies the action to perform
     * @param  array $attrs Attributes
     * @return mixed
     */
    function distributionListAction(
        DistListSelector $dl,
        DistListAction $action,
        array $attrs = array()
    );

    /**
     * End the current session, removing it from all caches.
     * Called when the browser app (or other session-using app) shuts down.
     * Has no effect if called in a <nosession> context.
     *
     * @return mixed
     */
    function endSession();

    /**
     * Returns groups the user is either a member or an owner of.
     * Notes:
     *   1. isOwner is returned only if ownerOf on the request is 1 (true).
     *   2. For owners/rights, only grants on the group itself will be modified,
     * For example, if isOwner="1" and isMember="none" on the request,
     * and user is an owner and a member of a group,
     * the returned entry for the group will have isOwner="1",
     * but isMember will not be present.
     *
     * @param  bool   $ownerOf  Set to 1 if the response should include groups the user is an owner of. Set to 0 (default) if do not need to know which groups the user is an owner of.
     * @param  string $memberOf Possible values: all - need all groups the user is a direct or indirect member of. none - do not need groups the user is a member of. directOnly (default) - need groups the account is a direct member of.
     * @param  string $attrs    Comma-seperated attributes to return
     * @return mixed
     */
    function getAccountDistributionLists(
        $ownerOf = null,
        $memberOf = null,
        $attrs = null
    );

    /**
     * Get Information about an account
     *
     * @param  Account $account Use to identify the account
     * @return mixed
     */
    function getAccountInfo(Account $account);

    /**
     * Returns all locales defined in the system
     *
     * @return mixed
     */
    function getAllLocales();

    /**
     * Returns the known CSV formats that can be used for import and export of addressbook.
     *
     * @return mixed
     */
    function getAvailableCsvFormats();

    /**
     * Get the intersection of all translated locales installed on the server
     * and the list specified in zimbraAvailableLocale
     * The locale list in the response is sorted by display name (name attribute).
     *
     * @return mixed
     */
    function getAvailableLocales();

    /**
     * Get the intersection of installed skins on the server and the list specified
     * in the zimbraAvailableSkin on an account (or its CoS).
     * If none is set in zimbraAvailableSkin, get the entire list of installed skins.
     * The installed skin list is obtained by a directory scan of the designated location
     * of skins on a server.
     *
     * @return mixed
     */
    function getAvailableSkins();

    /**
     * Get a distribution list, optionally with ownership information an granted rights.
     *
     * @param  string|DistListSelector   $dl Specify the distribution list
     * @param  bool   $needOwners Whether to return owners, default is 0 (i.e. Don't return owners)
     * @param  string $needRights Return grants for the specified (comma-seperated) rights. 
     * @param  array  $attrs Attributes of the distribution list
     * @return mixed
     */
    function getDistributionList(
        DistListSelector $dl,
        $needOwners = null,
        $needRights = null,
        array $attrs = array()
    );

    /**
     * Get the list of members of a distribution list.
     *
     * @param string $ld     The name of the distribution list
     * @param int    $limit  The number of members to return (0 is default and means all)
     * @param int    $offset The starting offset (0, 25, etc)
     * @return mixed
     */
    function getDistributionListMembers($dl, $limit = null, $offset = null);

    /**
     * Get the identities for the authed account.
     *
     * @param array $identities array of Identity
     * @return mixed
     */
    function getIdentities(array $identities = array());

    /**
     * Get information about an account by sections.
     * Sections are: mbox,prefs,attrs,zimlets,props,idents,sigs,dsrcs,children
     *
     * @param  string $sections Comma separated list of sections to return information about.
     * @param  string $rights   Comma separated list of rights to return information about.
     * @return mixed
     */
    function getInfo($sections = null, $rights = null);

    /**
     * Get preferences for the authenticated account 
     *
     * @param  array $prefs Array of preferences. 
     * @return mixed
     */
    function getPrefs(array $prefs = array());

    /**
     * Get account level rights. 
     *
     * @param  array $ace Specify Access Control Entries. 
     * @return mixed
     */
    function getRights(array $ace = array());

    /**
     * Get information about published shares
     *
     * @param  GranteeChooser  $grantee  Filter by the specified grantee type
     * @param  Account $owner  Specifies the owner of the share
     * @param  bool   $internal    Flags that have been proxied to this server because the specified "owner account" is homed here. Do not proxy in this case. (Used internally by ZCS)
     * @param  bool   $includeSelf Flag whether own shares should be included. 0 if shares owned by the requested account should not be included in the response. 1 (default) include shares owned by the requested account
     * @return mixed
     */
    function getShareInfo(
        GranteeChooser $grantee = null,
        Account $owner = null,
        $internal = null,
        $includeSelf = null
    );

    /**
     * Get Signatures associated with an account
     *
     * @return mixed
     */
    function getSignatures();

    /**
     * Get Signatures associated with an account
     * Note: This request will return a SOAP fault if the zimbraSoapExposeVersion
     *       server/globalconfig attribute is set to FALSE.
     *
     * @return mixed
     */
    function getVersionInfo();

    /**
     * Get the anti-spam WhiteList and BlackList addresses
     *
     * @return  mixed
     */
    function getWhiteBlackList();

    /**
     * Grant account level rights
     *
     * @param  array $ace Specify Access Control Entries
     * @return mixed
     */
    function grantRights(array $aces = array());

    /**
     * Modify an Identity
     *
     * @param  Identity $identity Specify identity to be modified Must specify either "name" or "id" attribute
     * @return mixed
     */
    function modifyIdentity(Identity $identity);

    /**
     * Modify preferences
     *
     * @param  array $pref Specify the preferences to be modified
     * @return mixed
     */
    function modifyPrefs(array $pref = array());

    /**
     * Modify properties related to zimlets
     *
     * @param  array $prop Specify the properties to be modified
     * @return mixed
     */
    function modifyProperties(array $prop = array());

    /**
     * Change attributes of the given signature
     * Only the attributes specified in the request are modified.
     * Note: The Server identifies the signature by id,
     *       if the name attribute is present and is different from the current name of the signature,
     *       the signature will be renamed.
     *
     * @param  Signature $signature Specifies the changes to the signature
     * @return mixed
     */
    function modifySignature(Signature $signature);

    /**
     * Modify the anti-spam WhiteList and BlackList addresses
     * Note: If no <addr> is present in a list, it means to remove all addresses in the list. 
     *
     * @param  array $whiteList White list
     * @param  array $blackList Black list
     * @return mixed
     */
    function modifyWhiteBlackList(
        array $whiteList,
        array $blackList = array()
    );

    /**
     * Modify Zimlet Preferences
     *
     * @param  array $zimlet Zimlet Preference Specifications
     * @return mixed
     */
    function modifyZimletPrefs(array $zimlet = array());

    /**
     * Revoke account level rights
     *
     * @param  array $ace Specify Access Control Entries
     * @return mixed
     */
    function revokeRights(array $ace = array());

    /**
     * Search Global Address List (GAL) for calendar resources
     * "attrs" attribute - comma-separated list of attrs to
     * return ("displayName", "zimbraId", "zimbraCalResType")
     *
     * @param CursorInfo $cursor Cursor specification
     * @param SearchFilter $searchFilter Search filter specification
     * @param string $name      If specified, passed through to the GAL search as the search key
     * @param string $locale    Client locale identification. 
     * @param bool   $quick     "Quick" flag. 
     * @param string $sortBy    Name of attribute to sort on. default is the calendar resource name.
     * @param int    $limit     An integer specifying the 0-based offset into the results list to return as the first result for this search operation
     * @param int    $offset    The 0-based offset into the results list to return as the first result for this search operation.
     * @param string $galAcctId GAL Account ID
     * @param string $attrs     Comma separated list of attributes
     * @return mixed
     */
    function searchCalendarResources(
        CursorInfo $cursor = null,
        SearchFilter $searchFilter = null,
        $name = null,
        $locale = null,
        $quick = null,
        $sortBy = null,
        $limit = null,
        $offset = null,
        $galAcctId = null,
        $attrs = null
    );

    /**
     * Search Global Address List (GAL)
     *
     * @param CursorInfo $cursor  Cursor specification
     * @param SearchFilter $searchFilter Search filter specification
     * @param string $locale      Client locale identification. 
     * @param string $ref         If set then search GAL by this ref, which is a dn. If specified then "name" attribute is ignored.
     * @param string $name        Query string. Note: ignored if {gal-search-ref-DN} is specified
     * @param string $type        Type of addresses to auto-complete on
     * @param bool   $needExp     flag whether the {exp} flag is needed in the response for group entries. Default is unset.
     * @param bool   $needIsOwner Set this if the "isOwner" flag is needed in the response for group entries. Default is unset.
     * @param string $needIsMember Specify if the "isMember" flag is needed in the response for group entries.
     * @param bool   $needSMIMECerts Internal attr, for proxied GSA search from GetSMIMEPublicCerts only
     * @param string $galAcctId   GAL Account ID
     * @param bool   $quick       "Quick" flag.
     * @param string $sortBy      SortBy setting. Default value is "dateDesc"
     * @param int    $limit       The maximum number of results to return. It defaults to 10 if not specified, and is
     * @param int    $offset      Specifies the 0-based offset into the results list to return as the first result for this search operation. 
     * @return mixed
     */
    function searchGal(
        CursorInfo $cursor = null,
        SearchFilter $searchFilter = null,
        $locale = null,
        $ref = null,
        $name = null,
        $type = null,
        $needExp = null,
        $needIsOwner = null,
        $needIsMember = null,
        $needSMIMECerts = null,
        $galAcctId = null,
        $quick = null,
        $sortBy = null,
        $limit = null,
        $offset = null
    );

    /**
     * Subscribe to a distribution list
     *
     * @param string $op
     * @param DistListSelector $dl
     * @return mixed
     */
    function subscribeDistributionList($op, DistListSelector $dl);

    /**
     * Synchronize with the Global Address List
     *
     * @param string $token     The previous synchronization token if applicable
     * @param string $galAcctId GAL sync account ID
     * @param bool   $idOnly    Flag whether only the ID attributes for matching contacts should be returned. 
     * @return mixed
     */
    function syncGal($token = null, $galAcctId = null, $idOnly = null);
}
