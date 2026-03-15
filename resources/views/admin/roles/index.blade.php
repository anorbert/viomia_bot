@extends('layouts.admin')

@push('styles')
<style>
.right_col { background-color: #0a0e17 !important; }
.vi-header { display: flex !important; align-items: center !important; flex-wrap: wrap; gap: 14px; background: linear-gradient(135deg, #1a2235 0%, #111827 100%) !important; border: 1px solid rgba(26,187,156,0.2) !important; border-top: 3px solid #1ABB9C !important; border-radius: 12px !important; padding: 20px 24px !important; margin-bottom: 20px !important; box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
.vi-header-title { font-size: 20px !important; font-weight: 900 !important; color: #f1f5f9 !important; }
.vi-header-sub { font-size: 12px !important; color: #94a3b8 !important; margin-top: 4px; }
.vi-panel { background: linear-gradient(135deg, #1a2235 0%, #111827 100%); border: 1px solid rgba(26,187,156,0.15); border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.3); margin-bottom: 20px; }
.vi-panel-title { font-size: 14px; font-weight: 700; color: #f1f5f9; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
.vi-panel-title i { color: #1ABB9C; font-size: 17px; }
.vi-table-container { overflow-x: auto; }
.vi-table { width: 100%; border-collapse: collapse; }
.vi-table thead { background-color: rgba(26,187,156,0.08); border-bottom: 1px solid rgba(26,187,156,0.2); }
.vi-table th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #1ABB9C; }
.vi-table td { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.07); color: #e2e8f0; font-size: 13px; }
.vi-table tbody tr { transition: background-color 0.2s; }
.vi-table tbody tr:hover { background-color: rgba(26,187,156,0.05); }
.vi-status-badge { display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-status-active { background: rgba(34,197,94,0.15); color: #22C55E; }
.vi-status-inactive { background: rgba(239,68,68,0.15); color: #ef4444; }
.vi-status-system { background: rgba(59,158,255,0.15); color: #3B9EFF; }
.vi-actions { display: flex; gap: 8px; }
.vi-btn { padding: 6px 12px; border-radius: 4px; font-weight: 700; border: none; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.5px; }
.vi-btn-edit { background-color: rgba(26,187,156,0.15); color: #1ABB9C; }
.vi-btn-edit:hover { background-color: rgba(26,187,156,0.25); box-shadow: 0 2px 8px rgba(26,187,156,0.15); }
.vi-btn-delete { background-color: rgba(239,68,68,0.15); color: #ef4444; }
.vi-btn-delete:hover { background-color: rgba(239,68,68,0.25); box-shadow: 0 2px 8px rgba(239,68,68,0.15); }
.vi-btn-primary { background-color: #1ABB9C !important; color: #fff !important; }
.vi-btn-primary:hover { background-color: #159d84 !important; box-shadow: 0 4px 12px rgba(26,187,156,0.3) !important; }
.vi-empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
.vi-empty-state-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
.vi-empty-state-text { font-size: 14px; margin-bottom: 24px; }
.vi-info-box { background: rgba(26,187,156,0.05); border: 1px solid rgba(26,187,156,0.15); border-left: 3px solid #1ABB9C; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; display: flex; gap: 12px; }
.vi-info-icon { color: #1ABB9C; font-size: 18px; flex-shrink: 0; }
.vi-info-text { color: #94a3b8; font-size: 12px; line-height: 1.5; }
.vi-header-actions { display: flex; gap: 12px; margin-left: auto; }
</style>
@endpush

@section('content')

<div style="max-width: 1000px; margin: 0 auto;">
    <div class="vi-header">
        <div>
            <div style="font-size:11px; font-weight:800; color:#1ABB9C; letter-spacing:2px; text-transform:uppercase; margin-bottom:6px;">👥 Role Management</div>
            <div class="vi-header-title">System Roles</div>
            <div class="vi-header-sub">Manage user roles and permissions</div>
        </div>
        <div class="vi-header-actions">
            <a href="{{ route('admin.roles.create') }}" class="vi-btn vi-btn-primary">
                <i class="fa fa-plus"></i> Create Role
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #22C55E; font-size: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #ef4444; font-size: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="vi-info-box">
        <div class="vi-info-icon"><i class="fa fa-info-circle"></i></div>
        <div class="vi-info-text">
            <strong>System Overview:</strong> System roles (Admin, Support, User) are protected and cannot be modified. Custom roles can be created, edited, or deleted as needed.
        </div>
    </div>

    <div class="vi-panel">
        <div class="vi-panel-title"><i class="fa fa-list"></i> All Roles</div>

        @if($roles->count() > 0)
            <div class="vi-table-container">
                <table class="vi-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="width: 40%;">Role Name</th>
                            <th style="width: 20%;">Status</th>
                            <th style="width: 15%;">Users</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td><strong>#{{ $role->id }}</strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-shield" style="color: #1ABB9C;"></i>
                                    <span>{{ ucfirst($role->name) }}</span>
                                    @if($role->isSystemRole())
                                    <span style="font-size: 9px; background: rgba(59,158,255,0.15); color: #3B9EFF; padding: 2px 6px; border-radius: 3px; font-weight: 700;">SYSTEM</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="vi-status-badge {{ $role->status === 'Active' ? 'vi-status-active' : 'vi-status-inactive' }}">
                                    <i class="fa {{ $role->status === 'Active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $role->status }}
                                </span>
                            </td>
                            <td>
                                <span style="background: rgba(26,187,156,0.1); color: #1ABB9C; padding: 4px 8px; border-radius: 4px; font-weight: 700; font-size: 12px;">
                                    {{ $role->getUserCount() }}
                                </span>
                            </td>
                            <td>
                                <div class="vi-actions">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="vi-btn vi-btn-edit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    @if(!$role->isSystemRole())
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vi-btn vi-btn-delete">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="vi-empty-state">
                <div class="vi-empty-state-icon"><i class="fa fa-inbox"></i></div>
                <div class="vi-empty-state-text">No roles found. Create one to get started.</div>
                <a href="{{ route('admin.roles.create') }}" class="vi-btn vi-btn-primary">
                    <i class="fa fa-plus"></i> Create First Role
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
