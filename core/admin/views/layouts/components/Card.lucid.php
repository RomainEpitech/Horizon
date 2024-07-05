<script>
    async function fetchUsers() {
        const query = `
            {
                users {
                    id
                    email
                    role
                    created_at
                }
            }
        `;

        try {
            const response = await fetch('/core/admin/graphql/graphql.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ query })
            });

            const text = await response.text();
            console.log('Raw response:', text);

            if (!text) {
                throw new Error('Empty response from server');
            }

            const result = JSON.parse(text);
            return result.data.users;
        } catch (error) {
            console.error('Error fetching users:', error);
            return [];
        }
    }

    function renderCards(users) {
        const container = document.getElementById('cards');
        container.innerHTML = users.map(user => `
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text">Email: ${user.email}</p>
                        <p class="card-text">Role: ${user.role}</p>
                        <p class="card-text">Joined: ${user.created_at}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const users = await fetchUsers();
        renderCards(users);
    });
</script>
