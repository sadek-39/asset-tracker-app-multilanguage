package com.sadekinflack.assettracker.repository;

import com.sadekinflack.assettracker.entity.IncomeSource;
import org.springframework.data.jpa.repository.JpaRepository;
import java.util.List;
import java.util.Optional;

public interface IncomeSourceRepository extends JpaRepository<IncomeSource, Long> {
    List<IncomeSource> findByUserId(Long userId);
    Optional<IncomeSource> findByIdAndUserId(Long id, Long userId);
}
