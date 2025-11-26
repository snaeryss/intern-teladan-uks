<?php

namespace App\Contracts;

/**
 * Contract untuk Medical Record Service
 * Mendefinisikan operasi-operasi yang bisa dilakukan pada medical records (DCU/MCU)
 */
interface MedicalRecordServiceInterface
{
    /**
     * Create atau update medical record (DCU/MCU)
     * 
     * @param string $type ('dcu' or 'mcu')
     * @param array $data
     * @return mixed
     */
    public function create(string $type, array $data);

    /**
     * Mark medical record as finished/completed
     * 
     * @param string $type ('dcu' or 'mcu')
     * @param int|string $id
     * @return bool
     */
    public function finish(string $type, int|string $id): bool;

    /**
     * Get medical record by ID dengan relasi lengkap
     * 
     * @param string $type ('dcu' or 'mcu')
     * @param int|string $id
     * @return mixed
     */
    public function findById(string $type, int|string $id);

    /**
     * Get existing medical record untuk student & period tertentu
     * 
     * @param string $type ('dcu' or 'mcu')
     * @param string $studentId
     * @param int $periodId
     * @return mixed|null
     */
    public function findByStudentAndPeriod(string $type, string $studentId, int $periodId);

    /**
     * Check apakah medical record sudah selesai
     * 
     * @param string $type ('dcu' or 'mcu')
     * @param int|string $id
     * @return bool
     */
    public function isFinished(string $type, int|string $id): bool;

    /**
     * Validate data sebelum create/update
     * Throw exception jika tidak valid
     * 
     * @param string $type ('dcu' or 'mcu')
     * @param array $data
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function validateData(string $type, array $data): bool;
}