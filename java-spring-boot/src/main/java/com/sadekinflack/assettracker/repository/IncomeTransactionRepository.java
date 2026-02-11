package com.sadekinflack.assettracker.repository;

import com.sadekinflack.assettracker.entity.IncomeTransaction;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.List;

public interface IncomeTransactionRepository extends JpaRepository<IncomeTransaction, Long> {
    List<IncomeTransaction> findByUserIdOrderByTransactionDateDesc(Long userId);
    
    @Query("SELECT SUM(i.amount) FROM IncomeTransaction i WHERE i.userId = :userId AND i.transactionDate >= :startDate")
    BigDecimal sumAmountByUserIdAndTransactionDateAfter(@Param("userId") Long userId, @Param("startDate") LocalDate startDate);
}
