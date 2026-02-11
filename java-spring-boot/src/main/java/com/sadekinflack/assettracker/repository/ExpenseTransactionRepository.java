package com.sadekinflack.assettracker.repository;

import com.sadekinflack.assettracker.entity.ExpenseTransaction;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.List;

public interface ExpenseTransactionRepository extends JpaRepository<ExpenseTransaction, Long> {
    List<ExpenseTransaction> findByUserIdOrderByTransactionDateDesc(Long userId);

    @Query("SELECT SUM(e.amount) FROM ExpenseTransaction e WHERE e.userId = :userId AND e.transactionDate >= :startDate")
    BigDecimal sumAmountByUserIdAndTransactionDateAfter(@Param("userId") Long userId, @Param("startDate") LocalDate startDate);
}
