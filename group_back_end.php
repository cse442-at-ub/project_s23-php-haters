// Define an array of users
$users = [
    ['id' => 1, 'name' => 'John'],
    ['id' => 2, 'name' => 'Jane'],
    ['id' => 3, 'name' => 'Bob'],
    ['id' => 4, 'name' => 'Sara'],
    ['id' => 5, 'name' => 'Mike'],
];

// Define an array of groups
$groups = [
['id' => 1, 'name' => 'Group A', 'users' => []],
['id' => 2, 'name' => 'Group B', 'users' => []],
['id' => 3, 'name' => 'Group C', 'users' => []],
];

// Function to add a user to a group
function addUserToGroup($userId, $groupId, &$users, &$groups) {
$user = getUserById($userId, $users);
$group = getGroupById($groupId, $groups);
if ($user && $group && !in_array($user, $group['users'])) {
$group['users'][] = $user;
return true;
}
return false;
}

// Function to remove a user from a group
function removeUserFromGroup($userId, $groupId, &$users, &$groups) {
$user = getUserById($userId, $users);
$group = getGroupById($groupId, $groups);
if ($user && $group) {
$key = array_search($user, $group['users']);
if ($key !== false) {
unset($group['users'][$key]);
return true;
}
}
return false;
}

// Function to get a user by ID
function getUserById($userId, $users) {
foreach ($users as $user) {
if ($user['id'] == $userId) {
return $user;
}
}
return null;
}

// Function to get a group by ID
function getGroupById($groupId, $groups) {
foreach ($groups as $group) {
if ($group['id'] == $groupId) {
return $group;
}
}
return null;
}

// Add users to groups
addUserToGroup(1, 1, $users, $groups);
addUserToGroup(2, 1, $users, $groups);
addUserToGroup(3, 1, $users, $groups);
addUserToGroup(4, 2, $users, $groups);
addUserToGroup(5, 3, $users, $groups);

// Remove a user from a group
removeUserFromGroup(2, 1, $users, $groups);

// Print out the groups and their users
foreach ($groups as $group) {
echo "Group " . $group['name'] . ":\n";
foreach ($group['users'] as $user) {
echo " - " . $user['name'] . "\n";
}
echo "\n";
}