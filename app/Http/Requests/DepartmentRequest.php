<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $department = $this->route('department');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'min:3',
                Rule::unique('departments')->ignore($department),
            ],
            'ip_range_start' => [
                'required',
                'ipv4',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $fail('La IP inicial no es una dirección IPv4 válida.');
                    }
                },
            ],
            'ip_range_end' => [
                'required',
                'ipv4',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $fail('La IP final no es una dirección IPv4 válida.');
                    }
                    
                    if ($this->ip_range_start && $this->ipToLong($value) < $this->ipToLong($this->ip_range_start)) {
                        $fail('La IP final debe ser mayor o igual que la IP inicial.');
                    }
                    
                    $this->validateIpRange($attribute, $value, $fail);
                },
            ],
        ];
    }

    /**
     * Convierte IP a número long (maneja correctamente IPs grandes)
     */
    protected function ipToLong(string $ip): float
    {
        return (float) sprintf('%u', ip2long($ip));
    }

    protected function validateIpRange($attribute, $value, $fail)
    {
        try {
            $department = $this->route('department');
            $start = $this->ip_range_start;
            $end = $this->ip_range_end;

            // Validación básica de IPs
            if (!$start || !$end || !filter_var($start, FILTER_VALIDATE_IP) || !filter_var($end, FILTER_VALIDATE_IP)) {
                return;
            }

            $startLong = $this->ipToLong($start);
            $endLong = $this->ipToLong($end);

            // Buscar rangos que se solapen
            $conflictingDepartments = Department::where(function($query) use ($startLong, $endLong) {
                $query->where(function($q) use ($startLong, $endLong) {
                    // Rango existente contiene el nuevo inicio
                    $q->whereRaw('INET_ATON(ip_range_start) <= ?', [$startLong])
                      ->whereRaw('INET_ATON(ip_range_end) >= ?', [$startLong]);
                })->orWhere(function($q) use ($startLong, $endLong) {
                    // Rango existente contiene el nuevo fin
                    $q->whereRaw('INET_ATON(ip_range_start) <= ?', [$endLong])
                      ->whereRaw('INET_ATON(ip_range_end) >= ?', [$endLong]);
                })->orWhere(function($q) use ($startLong, $endLong) {
                    // Nuevo rango contiene un rango existente
                    $q->whereRaw('INET_ATON(ip_range_start) >= ?', [$startLong])
                      ->whereRaw('INET_ATON(ip_range_end) <= ?', [$endLong]);
                });
            });

            if ($department) {
                $conflictingDepartments->where('id', '!=', $department->id);
            }

            if ($conflictingDepartments->exists()) {
                $conflict = $conflictingDepartments->first();
                $fail("El rango de IPs se solapa con el departamento existente: {$conflict->name} ({$conflict->ip_range_start} - {$conflict->ip_range_end})");
            }

        } catch (\Exception $e) {
            Log::error('Error en validación de rango IP', [
                'error' => $e->getMessage(),
                'data' => [
                    'start' => $this->ip_range_start,
                    'end' => $this->ip_range_end
                ]
            ]);
            $fail('Ocurrió un error al validar el rango de IPs.');
        }
    }

    public function messages(): array
    {
        return [
            'ip_range_start.required' => 'La IP inicial es obligatoria.',
            'ip_range_end.required' => 'La IP final es obligatoria.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ip_range_start' => trim($this->ip_range_start),
            'ip_range_end' => trim($this->ip_range_end),
        ]);
    }
}