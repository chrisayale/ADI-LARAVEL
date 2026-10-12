<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="title">Gestion des SACCOs</h2>

                    <?php if(auth()->user()->hasRole('super_admin')): ?>
                        <a href="<?php echo e(route('super-admin.sacos.create')); ?>" class="btn btn-primary btn-sm float-end">
                            Nouveaux SACCO
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Code</th>
                                    <th>Numéro d'enregistrement</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($sacos->isEmpty()): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Aucun SACCO trouvé.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $__currentLoopData = $sacos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sacco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sacco->id); ?></td>
                                            <td><?php echo e($sacco->name); ?></td>
                                            <td><?php echo e($sacco->code); ?></td>
                                            <td><?php echo e($sacco->registration_number ?? 'N/A'); ?></td>
                                            <td><?php echo e($sacco->phone ?? 'N/A'); ?></td>
                                            <td><?php echo e($sacco->email ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($sacco->status === 'active' ? 'success' : ($sacco->status === 'inactive' ? 'secondary' : 'warning')); ?>">
                                                    <?php echo e(ucfirst($sacco->status)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('super-admin.sacos.show', $sacco->id)); ?>" class="btn btn-info btn-sm">
                                                        Détails
                                                    </a>

                                                    <?php
                                                        $isSuperAdmin = auth()->user() && auth()->user()->hasRole('super_admin');
                                                    ?>
                                                    <?php if($isSuperAdmin): ?>
                                                        <a href="<?php echo e(route('super-admin.sacos.edit', $sacco->id)); ?>" class="btn btn-warning btn-sm">
                                                            Modifier
                                                        </a>

                                                        <!-- Activer/Désactiver SACCO button -->
                                                        <?php
                                                            $isActive = $sacco->status === 'active';
                                                            $actionText = $isActive ? 'désactiver' : 'activer';
                                                            $actionConfirmText = $isActive ? 'Désactiver' : 'Activer';
                                                        ?>
                                                        <a href="<?php echo e(route('super-admin.sacos.toggle-status', $sacco->id)); ?>"
                                                           class="btn btn-sm me-1 <?php echo e($isActive ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir <?php echo e($actionText); ?> ce SACCO ?');">
                                                            <i class="fas <?php echo e($isActive ? 'fa-times' : 'fa-check'); ?>"></i>
                                                            <?php echo e($actionConfirmText); ?>

                                                        </a>

                                                        <form action="<?php echo e(route('super-admin.sacos.destroy', $sacco->id)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce SACCO ? Cette action est irréversible.');">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td colspan="5" class="text-center p-3">
                                            <div class="bg-info text-white rounded p-2">
                                                <strong>DEBUG:</strong> View loaded successfully.
                                                Sac count: <?php echo e($sacos->count()); ?>.
                                                <?php
                                                    $user = auth()->user();
                                                    $userRoleInfo = $user && $user->roles ? $user->roles->pluck('name')->implode(', ') : ($user ? 'no roles' : 'not authenticated');
                                                ?>
                                                User role: <?php echo e($userRoleInfo); ?>.
                                                Is super admin: <?php echo e($user && $user->hasRole('super_admin') ? 'yes' : 'no'); ?>.
                                            </div>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
