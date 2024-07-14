const ManagerSidebar = `
    <ul>
         <li><a href="/superadmin">Home</a></li>
        <li><a href="/superadmin/requests">Requests</a></li>
    </ul>
`
if (document.getElementsByClassName('SuperAdminSidebar')[0]){
    document.getElementsByClassName('SuperAdminSidebar')[0].innerHTML = ManagerSidebar;
}