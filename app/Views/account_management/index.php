<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Account Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-people mr-3 text-green-200"></i>
                        Account Management
                    </h1>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="<?= base_url('accounts/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Create New Account
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Filter Section -->
                <div class="mb-6">
                    <form method="GET" action="<?= base_url('accounts') ?>" class="bg-gray-50 rounded-xl p-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">All Roles</option>
                                    <option value="disbursing_officer" <?= $filters['role'] === 'disbursing_officer' ? 'selected' : '' ?>>Disbursing Officer</option>
                                    <option value="scholarship_coordinator" <?= $filters['role'] === 'scholarship_coordinator' ? 'selected' : '' ?>>Scholarship Coordinator</option>
                                    <option value="scholarship_chairman" <?= $filters['role'] === 'scholarship_chairman' ? 'selected' : '' ?>>Scholarship Chairman</option>
                                    <option value="accounting_officer" <?= $filters['role'] === 'accounting_officer' ? 'selected' : '' ?>>Accounting Officer</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                                <select name="campus" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">All Campuses</option>
                                    <option value="Main Campus" <?= $filters['campus'] === 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                                    <option value="Kalamansig Campus" <?= $filters['campus'] === 'Kalamansig Campus' ? 'selected' : '' ?>>Kalamansig Campus</option>
                                    <option value="Palimbang Campus" <?= $filters['campus'] === 'Palimbang Campus' ? 'selected' : '' ?>>Palimbang Campus</option>
                                    <option value="Isulan Campus" <?= $filters['campus'] === 'Isulan Campus' ? 'selected' : '' ?>>Isulan Campus</option>
                                    <option value="Bagumbayan Campus" <?= $filters['campus'] === 'Bagumbayan Campus' ? 'selected' : '' ?>>Bagumbayan Campus</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">All Status</option>
                                    <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" value="<?= esc($filters['search']) ?>" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="Username or Email">
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-funnel mr-2"></i>
                                Apply Filters
                            </button>
                            <a href="<?= base_url('accounts') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-arrow-clockwise mr-2"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="mb-8">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-person-x text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-500">No users match your current filter criteria.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 data-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($users as $user_item): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= esc($user_item['username']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= esc($user_item['email']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?= esc(ucwords(str_replace('_', ' ', $user_item['role']))) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= esc($user_item['campus']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if ($user_item['is_active']): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="bi bi-check-circle mr-1"></i>
                                                    Active
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="bi bi-x-circle mr-1"></i>
                                                    Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($user_item['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="<?= base_url('accounts/edit/' . $user_item['id']) ?>" 
                                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                
                                                <?php if ($user_item['id'] != $user['id']): ?>
                                                <form method="POST" action="<?= base_url('accounts/toggle-status/' . $user_item['id']) ?>" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" 
                                                            class="<?= $user_item['is_active'] ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' ?>" 
                                                            title="<?= $user_item['is_active'] ? 'Deactivate' : 'Activate' ?>"
                                                            onclick="return confirm('Are you sure you want to <?= $user_item['is_active'] ? 'deactivate' : 'activate' ?> this account?')">
                                                        <i class="bi bi-<?= $user_item['is_active'] ? 'pause-circle' : 'play-circle' ?>"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>