// document.addEventListener('DOMContentLoaded', function() {
//     function fetchUsers() {
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'fetchUsers.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 var responseData = JSON.parse(xhr.responseText);
//                 displayUsers(responseData);
//             }
//         };
//         xhr.send('fetchUsers=true');
//     }

//     fetchUsers();

//     function displayUsers(users) {
//         const userTable = document.getElementById("userTable").querySelector("tbody");
//         userTable.innerHTML = "";

//         users.forEach(user => {
//             const row = document.createElement('tr');
//             row.innerHTML = 
//                 "<td>" + user.id + "</td>" +
//                 "<td>" + user.email + "</td>" +
//                 "<td>" + user.username + "</td>" +
//                 "<td>" + user.created_at + "</td>" +
//                 "<td>" + user.theme + "</td>" +
//                 "<td>" + (user.archived == 1 ? 'Yes' : 'No') + "</td>" +
//                 "<td>" + (user.isAdmin == 1 ? 'Yes' : 'No') + "</td>" +
//                 "<td>" + (user.isTutor == 1 ? 'Yes' : 'No') + "</td>" +
//                 "<td>" +
//                     "<button onclick=\"editUser(" + user.id + ")\">Edit</button>" +
//                     "<button onclick=\"toggleArchive(" + user.id + ", " + user.archived + ")\">" +
//                         (user.archived == 1 ? 'Unlock' : 'Lock') +
//                     "</button>" +
//                 "</td>";
//             userTable.appendChild(row);
//         });
//     }


//     function toggleArchive(userId, isArchived) {
//         const action = isArchived ? "unlock" : "lock";
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'toggleArchive.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 try{
//                     var responseData = JSON.parse(xhr.responseText);
//                     if(responseData.success){    
//                         fetchUsers();
//                     } else {
//                         alert("Error toggling lock on user: " + responseData.error);
//                     }

//                 } catch (e){
//                     console.error("Error parsing response: " + e)
//                 }
//             }
//         };
//         xhr.send("id=" + userId + "&action=" + action);
//     }

//     window.toggleArchive = toggleArchive;
    

//     function truncateText(text, maxLength) {
//         if (text.length > maxLength) {
//             return text.substring(0, maxLength) + "&nbsp;&nbsp;&nbsp;...(CONTINUED)";
//         }
//         return text;
//     }
    


//     function displayThreads(threads) {
//         const threadTable = document.getElementById("threadTable").querySelector("tbody");
//         threadTable.innerHTML = "";

//         threads.forEach(thread => {
//             const row = document.createElement('tr');
//             row.innerHTML = 
//                 "<td>" + thread.id + "</td>" +
//                 "<td>" + thread.user_id + "</td>" +
//                 "<td>" + thread.title + "</td>" +
//                 "<td>" + truncateText(thread.content, 500) + "</td>" +
//                 "<td>" + thread.created_at + "</td>" +
//                 "<td>" + (thread.archived == 1 ? 'Yes' : 'No') + "</td>" +
//                 "<td>" +
//                     "<button onclick=\"toggleArchiveThread(" + thread.id + ", " + thread.archived + ")\">" +
//                         (thread.archived == 1 ? 'Unlock' : 'Lock') +
//                     "</button>" +
//                 "</td>";
//             threadTable.appendChild(row);
//         });
//     }

//     function fetchThreads() {
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'fetchThreads.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 var responseData = JSON.parse(xhr.responseText);
//                 displayThreads(responseData);
//             }
//         };
//         xhr.send('fetchThreads=true');
//     }

//     fetchThreads();

//     function toggleArchiveThread(threadId, isArchived) {
//         const action = isArchived ? "unlock" : "lock";
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'toggleThread.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 try{
//                     var responseData = JSON.parse(xhr.responseText);
//                     if(responseData.success){    
//                         fetchThreads();
//                     } else {
//                         alert("Error toggling lock on thread: " + responseData.error);
//                     }

//                 } catch (e){
//                     console.error("Error parsing response: " + e)
//                 }
//             }
//         };
//         xhr.send("id=" + threadId + "&action=" + action);
//     }

//     window.toggleArchiveThread = toggleArchiveThread;



//     function displayReplies(replies) {
//         const replyTable = document.getElementById("replyTable").querySelector("tbody");
//         replyTable.innerHTML = "";

//         replies.forEach(reply => {
//             const row = document.createElement('tr');
//             row.innerHTML = 
//                 "<td>" + reply.id + "</td>" +
//                 "<td>" + reply.user_id + "</td>" +
//                 "<td>" + reply.thread_id + "</td>" +
//                 "<td>" + truncateText(reply.content, 500) + "</td>" +
//                 "<td>" + reply.created_at + "</td>" +
//                 "<td>" + (reply.archived == 1 ? 'Yes' : 'No') + "</td>" +
//                 "<td>" +
//                     "<button onclick=\"toggleArchiveReply(" + reply.id + ", " + reply.archived + ")\">" +
//                         (reply.archived == 1 ? 'Unlock' : 'Lock') +
//                     "</button>" +
//                 "</td>";
//             replyTable.appendChild(row);
//         });
//     }

//     function fetchReplies() {
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'fetchReplies.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 var responseData = JSON.parse(xhr.responseText);
//                 displayReplies(responseData);
//             }
//         };
//         xhr.send('fetchReplies=true');
//     }

//     fetchReplies();

//     function toggleArchiveReply(replyId, isArchived) {
//         const action = isArchived ? "unlock" : "lock";
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'toggleReply.php', true);
//         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 try{
//                     var responseData = JSON.parse(xhr.responseText);
//                     if(responseData.success){    
//                         fetchReplies();
//                     } else {
//                         alert("Error toggling lock on thread: " + responseData.error);
//                     }

//                 } catch (e){
//                     console.error("Error parsing response: " + e)
//                 }
//             }
//         };
//         xhr.send("id=" + replyId + "&action=" + action);
//     }

//     window.toggleArchiveReply = toggleArchiveReply;

// });