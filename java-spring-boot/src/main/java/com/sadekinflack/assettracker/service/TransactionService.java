package com.sadekinflack.assettracker.service;

import com.sadekinflack.assettracker.entity.ExpenseTransaction;
import com.sadekinflack.assettracker.entity.IncomeSource;
import com.sadekinflack.assettracker.entity.IncomeTransaction;
import com.sadekinflack.assettracker.repository.ExpenseTransactionRepository;
import com.sadekinflack.assettracker.repository.IncomeSourceRepository;
import com.sadekinflack.assettracker.repository.IncomeTransactionRepository;
import com.sadekinflack.assettracker.repository.UserRepository;
import com.sadekinflack.assettracker.security.SecurityUtils;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
public class TransactionService {

    private final IncomeTransactionRepository incomeTransactionRepository;
    private final ExpenseTransactionRepository expenseTransactionRepository;
    private final IncomeSourceRepository incomeSourceRepository;
    private final UserRepository userRepository;

    public TransactionService(IncomeTransactionRepository incomeTransactionRepository,
                               ExpenseTransactionRepository expenseTransactionRepository,
                               IncomeSourceRepository incomeSourceRepository,
                               UserRepository userRepository) {
        this.incomeTransactionRepository = incomeTransactionRepository;
        this.expenseTransactionRepository = expenseTransactionRepository;
        this.incomeSourceRepository = incomeSourceRepository;
        this.userRepository = userRepository;
    }

    @Transactional
    public IncomeTransaction createIncomeTransaction(IncomeTransaction tx) {
        Long userId = SecurityUtils.getCurrentUserId(userRepository);
        tx.setUserId(userId);
        
        IncomeSource source = incomeSourceRepository.findByIdAndUserId(tx.getSourceId(), userId)
                .orElseThrow(() -> new RuntimeException("Source not found"));
        
        source.setCurrentBalance(source.getCurrentBalance().add(tx.getAmount()));
        incomeSourceRepository.save(source);
        
        return incomeTransactionRepository.save(tx);
    }

    @Transactional
    public ExpenseTransaction createExpenseTransaction(ExpenseTransaction tx) {
        Long userId = SecurityUtils.getCurrentUserId(userRepository);
        tx.setUserId(userId);

        IncomeSource source = incomeSourceRepository.findByIdAndUserId(tx.getSourceId(), userId)
                .orElseThrow(() -> new RuntimeException("Source not found"));

        if (source.getCurrentBalance().compareTo(tx.getAmount()) < 0) {
            throw new RuntimeException("Insufficient balance");
        }

        source.setCurrentBalance(source.getCurrentBalance().subtract(tx.getAmount()));
        incomeSourceRepository.save(source);

        return expenseTransactionRepository.save(tx);
    }

    public List<IncomeTransaction> getIncomeTransactions() {
        Long userId = SecurityUtils.getCurrentUserId(userRepository);
        return incomeTransactionRepository.findByUserIdOrderByTransactionDateDesc(userId);
    }

    public List<ExpenseTransaction> getExpenseTransactions() {
        Long userId = SecurityUtils.getCurrentUserId(userRepository);
        return expenseTransactionRepository.findByUserIdOrderByTransactionDateDesc(userId);
    }
}
