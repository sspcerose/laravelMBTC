<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Notification Example</title>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">

    <div class="relative">
        <!-- Notification Icon -->
        <button id="notification-icon" class="relative p-2 bg-blue-500 text-white rounded-full focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 4a8 8 0 00-8 8v5h16v-5a8 8 0 00-8-8z" />
            </svg>
            <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">3</span>
        </button>

        <!-- Notification Container -->
        <div id="notification-container" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-10">
            <ul class="p-4" id="notification-list">
                <!-- Notifications will be injected here -->
            </ul>
        </div>
    </div>

    <script>
        let seenNotifications = []; // Array to track seen notifications
        let notificationCount = 0; // Current notification count

        // Mock function to simulate fetching notifications from a server
        async function fetchNotifications() {
            // Simulate a delay for fetching data
            // await new Promise(resolve => setTimeout(resolve, 1000));

            // Mock notifications data
            const notifications = [
                { id: 1, message: "Notification 1" },
                { id: 2, message: "Notification 2" },
                { id: 3, message: "Notification 3" },
                { id: 4, message: "Notification 4" },
                { id: 5, message: "Notification 5" },
                { id: 6, message: "Notification 6" },
                { id: 7, message: "Notification 7" },
                { id: 8, message: "Notification 8" },
                { id: 9, message: "Notification 9" },
                { id: 10, message: "Notification 10" },
                { id: 11, message: "Notification 11" },
            ];

            // Filter out seen notifications
            const newNotifications = notifications.filter(notification => !seenNotifications.includes(notification.id));

            // Update the notification count
            notificationCount = newNotifications.length;
            updateNotificationBadge(notificationCount);

            // Display notifications
            displayNotifications(newNotifications);
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            badge.textContent = count > 0 ? count : ''; // Update badge or hide if count is zero
        }

        function displayNotifications(notifications) {
            const container = document.getElementById('notification-list');
            container.innerHTML = ''; // Clear previous notifications

            notifications.forEach(notification => {
                const li = document.createElement('li');
                li.className = "py-2 border-b cursor-pointer hover:bg-gray-100"; // Add styles
                li.textContent = notification.message; // Display notification message
                li.onclick = () => markAsSeen(notification.id); // Mark as seen on click
                container.appendChild(li);
            });

            // Add "See More" button if there are more than 10 notifications
            if (notifications.length > 10) {
                const seeMoreButton = document.createElement('button');
                seeMoreButton.textContent = 'See More';
                seeMoreButton.className = "mt-2 text -blue-500 hover:underline";
                seeMoreButton.onclick = fetchMoreNotifications; // Fetch more notifications
                container.appendChild(seeMoreButton);
            }
        }

        function markAsSeen(notificationId) {
            seenNotifications.push(notificationId); // Add to seen notifications
            // Optionally update the database to mark this notification as seen
        }

        // Call fetchNotifications when the notification icon is clicked
        document.getElementById('notification-icon').addEventListener('click', () => {
            const container = document.getElementById('notification-container');
            container.classList.toggle('hidden'); // Toggle visibility of the notification container
            fetchNotifications(); // Fetch notifications when icon is clicked
        });
    </script>
</body>
</html>