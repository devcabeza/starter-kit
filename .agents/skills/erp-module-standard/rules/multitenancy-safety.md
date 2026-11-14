# Multi-Tenancy & Security Rules for ERP Modules

## Mandatory Rules

1. **Active Tenant Identification**:
   - Always resolve the tenant from `TenantContextInterface` or `auth()->user()->currentOrganization`.
   - Never accept an `organization_id` from client parameters or form inputs.

2. **Query Scoping**:
   - In `list`: Scope queries to the active organization:
     ```php
     $query = Model::where('organization_id', $org->id);
     ```
   - In `store`: Force the organization ID before persisting:
     ```php
     $data['organization_id'] = $org->id;
     Model::create($data);
     ```
   - In `edit` and `show`: Validate ownership with `where('organization_id', $org->id)->findOrFail($id)`.

3. **Unique Constraints**:
   - Unique validations must be scoped to the tenant:
     ```php
     use Illuminate\Validation\Rule;

     Rule::unique('models', 'code')
         ->where('organization_id', $org->id)
         ->ignore($this->editingId);
     ```
