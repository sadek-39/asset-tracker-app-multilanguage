package com.sadekinflack.assettracker.service;

import com.sadekinflack.assettracker.entity.Asset;
import com.sadekinflack.assettracker.entity.IncomeSource;
import com.sadekinflack.assettracker.repository.*;
import com.sadekinflack.assettracker.security.SecurityUtils;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.HashMap;
import java.util.Map;

@Service
public class DashboardService {
    private final IncomeTransactionRepository incomeRepo;
    private final ExpenseTransactionRepository expenseRepo;
    private final IncomeSourceRepository sourceRepo;
    private final AssetRepository assetRepo;
    private final UserRepository userRepository;

    public DashboardService(IncomeTransactionRepository incomeRepo, ExpenseTransactionRepository expenseRepo,
                            IncomeSourceRepository sourceRepo, AssetRepository assetRepo, UserRepository userRepository) {
        this.incomeRepo = incomeRepo;
        this.expenseRepo = expenseRepo;
        this.sourceRepo = sourceRepo;
        this.assetRepo = assetRepo;
        this.userRepository = userRepository;
    }

    public Map<String, Object> getStats() {
        Long userId = SecurityUtils.getCurrentUserId(userRepository);
        LocalDate startOfMonth = LocalDate.now().withDayOfMonth(1);

        BigDecimal totalIncome = incomeRepo.sumAmountByUserIdAndTransactionDateAfter(userId, startOfMonth);
        BigDecimal totalExpenses = expenseRepo.sumAmountByUserIdAndTransactionDateAfter(userId, startOfMonth);
        
        BigDecimal currentBalance = sourceRepo.findByUserId(userId).stream()
                .map(IncomeSource::getCurrentBalance)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        BigDecimal totalAssets = assetRepo.findByUserId(userId).stream()
                .map(Asset::getCurrentValue)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        Map<String, Object> stats = new HashMap<>();
        stats.put("totalIncome", totalIncome != null ? totalIncome : BigDecimal.ZERO);
        stats.put("totalExpenses", totalExpenses != null ? totalExpenses : BigDecimal.ZERO);
        stats.put("currentBalance", currentBalance);
        stats.put("totalAssets", totalAssets);

        return stats;
    }
}
